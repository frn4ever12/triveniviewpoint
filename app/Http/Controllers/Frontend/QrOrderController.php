<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;
use App\Models\Tenant;
use App\Models\WaiterCall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QrOrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        \Log::info('QR Order request received', $request->all());

        $validated = $request->validate([
            'tenant_slug' => 'required|string',
            'table_id' => 'required|integer',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'customer_name' => 'nullable|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Verify tenant exists and is active
        \Log::info('Looking for tenant with slug', ['slug' => $validated['tenant_slug']]);
        $tenant = Tenant::where('slug', $validated['tenant_slug'])
            ->where('status', 'active')
            ->firstOrFail();
        \Log::info('Tenant found', ['tenant_id' => $tenant->id, 'tenant_name' => $tenant->name]);

        // Verify table belongs to tenant
        $table = Table::where('id', $validated['table_id'])
            ->where('tenant_id', $tenant->id)
            ->firstOrFail();

        // Verify all menu items belong to tenant and are active
        $menuItemIds = collect($validated['items'])->pluck('menu_item_id');
        $menuItems = \App\Models\MenuItem::whereIn('id', $menuItemIds)
            ->where('tenant_id', $tenant->id)
            ->where('status', 'active')
            ->get();

        if ($menuItems->count() !== $menuItemIds->count()) {
            return response()->json([
                'success' => false,
                'message' => 'Some items are not available',
            ], 400);
        }

        // Validate prices from server-side
        foreach ($validated['items'] as $item) {
            $menuItem = $menuItems->firstWhere('id', $item['menu_item_id']);
            if (!$menuItem || abs($menuItem->price - $item['unit_price']) > 0.01) {
                return response()->json([
                    'success' => false,
                    'message' => 'Price mismatch detected',
                ], 400);
            }
        }

        DB::beginTransaction();
        try {
            // Calculate totals
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $subtotal += $item['unit_price'] * $item['quantity'];
            }

            // Use tenant's VAT and service charge settings
            $vatPercent = $tenant->vat_percent ?? 13;
            $serviceCharge = $tenant->service_charge_percent ?? 0;
            $serviceChargeAmount = round($subtotal * ($serviceCharge / 100), 2);
            $taxableAmount = $subtotal + $serviceChargeAmount;
            $vatAmount = round($taxableAmount * ($vatPercent / 100), 2);
            $totalAmount = $taxableAmount + $vatAmount;

            // Generate order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(Order::where('tenant_id', $tenant->id)->count() + 1, 4, '0', STR_PAD_LEFT);

            // Generate customer session token
            $sessionToken = Str::random(32);

            // Create order
            $order = Order::withoutGlobalScopes()->create([
                'tenant_id' => $tenant->id,
                'order_no' => $orderNumber,
                'table_id' => $table->id,
                'customer_name' => $validated['customer_name'] ?? null,
                'customer_phone' => $validated['customer_phone'] ?? null,
                'customer_session_token' => $sessionToken,
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending',
                'order_type' => 'dine_in',
                'order_source' => 'qr',
                'payment_status' => 'pending',
                'subtotal' => $subtotal,
                'vat_percent' => $vatPercent,
                'vat_amount' => $vatAmount,
                'total_amount' => $totalAmount,
            ]);

            // Create order items
            foreach ($validated['items'] as $item) {
                OrderItem::withoutGlobalScopes()->create([
                    'tenant_id' => $tenant->id,
                    'order_id' => $order->id,
                    'menu_item_id' => $item['menu_item_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['unit_price'] * $item['quantity'],
                    'status' => 'pending',
                    'is_kitchen_item' => true,
                    'size' => $item['size'] ?? 1,
                ]);
            }

            // Update table status
            $table->update(['status' => 'occupied']);

            // Create invoice
            $invoice = \App\Models\Invoice::withoutGlobalScopes()->create([
                'tenant_id' => $tenant->id,
                'order_id' => $order->id,
                'customer_name' => $validated['customer_name'] ?? null,
                'customer_phone' => $validated['customer_phone'] ?? null,
                'invoice_number' => 'INV-' . date('Ymd') . '-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                'subtotal' => $subtotal,
                'vat_percent' => $vatPercent,
                'vat_amount' => $vatAmount,
                'service_charge' => $serviceChargeAmount,
                'total_amount' => $totalAmount,
                'payment_status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Create KOT
            $this->createKOT($order, $tenant->id);

            DB::commit();

            // Load relationships
            $order->load(['items.menuItem', 'table', 'invoice']);

            // Notify restaurant staff (simple approach - store in session/database for polling)
            $this->notifyStaff($order, $tenant);

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully',
                'order' => $order,
                'session_token' => $sessionToken,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('QR Order placement failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order. Please try again.',
            ], 500);
        }
    }

    public function trackOrder($sessionToken, $orderNo)
    {
        $order = Order::withoutGlobalScopes()
            ->where('customer_session_token', $sessionToken)
            ->where('order_no', $orderNo)
            ->with(['items.menuItem', 'table', 'invoice'])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'order' => $order,
        ]);
    }

    private function createKOT(Order $order, $tenantId)
    {
        $date = now()->format('Ymd');
        $lastKot = \App\Models\Kot::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('kot_number', 'like', "KOT-{$date}%")
            ->orderBy('kot_number', 'desc')
            ->first();

        $newNumber = $lastKot ? (int) substr($lastKot->kot_number, -3) + 1 : 1;
        $kotNumber = "KOT-{$date}-" . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        while (\App\Models\Kot::withoutGlobalScopes()->where('kot_number', $kotNumber)->exists()) {
            $newNumber++;
            $kotNumber = "KOT-{$date}-" . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
        }

        $kot = \App\Models\Kot::withoutGlobalScopes()->create([
            'tenant_id' => $tenantId,
            'order_id' => $order->id,
            'kot_number' => $kotNumber,
            'status' => 'sent',
            'sent_at' => now(),
            'remarks' => 'QR Order - ' . $order->table->name,
        ]);

        $order->items()->where('is_kitchen_item', true)->update([
            'kot_id' => $kot->id,
            'kot_printed_at' => now(),
        ]);

        $order->update([
            'kot_sent_at' => now(),
            'status' => 'confirmed',
        ]);

        return $kot;
    }

    private function notifyStaff(Order $order, Tenant $tenant)
    {
        // Store notification in database for staff to see
        // This is a simple polling-based notification system
        $notification = \App\Models\Notification::create([
            'tenant_id' => $tenant->id,
            'type' => 'qr_order',
            'title' => 'New QR Order',
            'message' => "Table {$order->table->name} - Order #{$order->order_no} - {$order->items->count()} items - Rs {$order->total_amount}",
            'data' => json_encode([
                'order_id' => $order->id,
                'order_no' => $order->order_no,
                'table_id' => $order->table_id,
                'table_name' => $order->table->name,
                'items_count' => $order->items->count(),
                'total_amount' => $order->total_amount,
            ]),
            'read' => false,
        ]);

        return $notification;
    }

    public function callWaiter(Request $request)
    {
        $validated = $request->validate([
            'tenant_slug' => 'required|string',
            'table_id' => 'required|integer',
        ]);

        $tenant = Tenant::where('slug', $validated['tenant_slug'])
            ->where('status', 'active')
            ->firstOrFail();

        $table = Table::where('id', $validated['table_id'])
            ->where('tenant_id', $tenant->id)
            ->firstOrFail();

        // Check if there's already a pending call for this table
        $existingCall = WaiterCall::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('table_id', $table->id)
            ->where('status', 'pending')
            ->where('created_at', '>', now()->subMinutes(2))
            ->first();

        if ($existingCall) {
            return response()->json([
                'success' => false,
                'message' => 'Waiter has already been called. Please wait.',
            ], 429);
        }

        $waiterCall = WaiterCall::withoutGlobalScopes()->create([
            'tenant_id' => $tenant->id,
            'table_id' => $table->id,
            'status' => 'pending',
        ]);

        // Create notification for staff
        try {
            $notification = Notification::withoutGlobalScopes()->create([
                'tenant_id' => $tenant->id,
                'type' => 'waiter_call',
                'title' => 'Waiter Call',
                'message' => "Table {$table->name} - Customer is requesting assistance",
                'data' => json_encode([
                    'waiter_call_id' => $waiterCall->id,
                    'table_id' => $table->id,
                    'table_name' => $table->name,
                ]),
                'read' => false,
            ]);
            
            \Log::info('Waiter call notification created', [
                'notification_id' => $notification->id,
                'tenant_id' => $tenant->id,
                'table_id' => $table->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to create waiter call notification', [
                'error' => $e->getMessage(),
                'tenant_id' => $tenant->id,
                'table_id' => $table->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Waiter has been called',
        ]);
    }

    public function getWaiterCallStatus(Request $request)
    {
        $validated = $request->validate([
            'tenant_slug' => 'required|string',
            'table_id' => 'required|integer',
        ]);

        $tenant = Tenant::where('slug', $validated['tenant_slug'])
            ->where('status', 'active')
            ->firstOrFail();

        $table = Table::where('id', $validated['table_id'])
            ->where('tenant_id', $tenant->id)
            ->firstOrFail();

        $recentCall = WaiterCall::withoutGlobalScopes()
            ->where('tenant_id', $tenant->id)
            ->where('table_id', $table->id)
            ->where('created_at', '>', now()->subMinutes(30))
            ->orderBy('created_at', 'desc')
            ->first();

        return response()->json([
            'success' => true,
            'call' => $recentCall,
        ]);
    }
}
