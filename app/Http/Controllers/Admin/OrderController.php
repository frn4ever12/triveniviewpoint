<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Kot;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Recipe;
use App\Models\Table;
use App\Models\User;
use App\Services\InventoryTransactionService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryTransactionService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index()
    {
        $tables = Table::all();
        $menuItems = MenuItem::with('category')->get();
        $categories = Category::orderBy('name')->get();
        $waiters = User::get();

        // Backward-compat: pass legacy variable names for existing views
        $dishes = $menuItems;
        $menus = $categories;

        return view('admin.order.index', compact(
            'tables', 'menuItems', 'waiters',
            'dishes', 'menus', 'categories'
        ));
    }

    public function store(StoreOrderRequest $request)
    {
        DB::beginTransaction();
        try {
            // Calculate subtotal from items
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['unit_price'] * $item['quantity'];
            }

            // Get VAT and service charge from request or use defaults
            $vatPercent = $request->input('vat_percent', 0);
            $serviceCharge = $request->input('service_charge', 0);

            // Calculate VAT on (subtotal + service charge)
            $taxableAmount = $subtotal + $serviceCharge;
            $vatAmount = round($taxableAmount * ($vatPercent / 100), 2);

            // Calculate total
            $totalAmount = $taxableAmount + $vatAmount;

            // Generate unique order number
            $orderNumber = $this->generateOrderNumber();

            // Create order
            $order = Order::create([
                'order_no' => $orderNumber,
                'table_id' => $request->table_id ?? null,
                'waiter_id' => $request->waiter_id,
                'entry_user_id' => Auth::id(),
                'no_of_guests' => $request->no_of_guests,
                'notes' => $request->notes,
                'status' => 'pending',
                'order_type' => $request->order_type ?? 'dine_in',
                'payment_status' => 'pending',
            ]);

            // Create order items with default status as 'pending'
            foreach ($request->items as $item) {
                OrderItem::create([
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

            if (isset($request->table_id)) {
                // Update table status
                $table = Table::findOrFail($request->table_id);
                $table->update(['status' => 'occupied']);
            }

            // Create invoice
            $invoice = Invoice::create([
                'order_id' => $order->id,
                'customer_name' => $request->customer_name ?? null,
                'customer_phone' => $request->customer_phone ?? null,
                'delivery_address' => $request->delivery_address ?? null,
                'invoice_number' => $this->generateInvoiceNumber(),
                'subtotal' => $subtotal,
                'vat_percent' => $vatPercent,
                'vat_amount' => $vatAmount,
                'service_charge' => $serviceCharge,
                'total_amount' => $totalAmount,
                'payment_status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Create KOT
            $kot = $this->createKOT($order);

            DB::commit();

            // Load relationships for response
            $order->load(['items.menuItem', 'table', 'waiter', 'invoice']);

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order' => $order,
                'kot' => $kot->load('items.menuItem'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: '.$e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Order $order)
    {
        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending orders can be deleted',
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Reverse inventory if it was deducted
            $this->reverseInventoryForOrder($order);

            // Free up the table
            if ($order->table) {
                $order->table->update(['status' => 'available']);
            }

            $order->delete();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete order: '.$e->getMessage(),
            ], 500);
        }
    }

    public function getRecentOrders()
    {
        $tables = Table::with(['orders' => function ($q) {
            $q->where('status', '!=', 'completed')
                ->where(function ($q) {
                    $q->whereNull('payment_status')
                        ->orWhere('payment_status', '!=', 'paid');
                })
                ->with(['items.menuItem', 'invoice', 'waiter'])
                ->latest();
        }])->where('status', 'occupied')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'tables' => $tables->map(fn ($table) => [
                'id' => $table->id,
                'name' => $table->name,
                'status' => $table->status->value,
                'orders' => $table->orders->map(fn ($order) => [
                    'id' => $order->id,
                    'order_no' => $order->order_no,
                    'items' => $order->items->map(fn ($item) => [
                        'id' => $item->id,
                        'name' => $item->menuItem->name,
                        'qty' => $item->quantity,
                        'status' => $item->status ?? 'pending',
                    ]),
                    'items_count' => $order->items->sum('quantity'),
                    'total_amount' => $order->invoice->total_amount ?? 0,
                    'status' => $order->status,
                    'created_at' => $order->created_at->diffForHumans(),
                    'waiter' => $order->waiter?->name,
                ]),
            ]),
        ]);
    }

    /**
     * Get active orders for KOT display
     */
    public function getActiveOrders()
    {
        $orders = Order::with(['table', 'items.menuItem', 'waiter', 'invoice'])
            ->whereIn('status', ['pending', 'confirmed', 'served'])
            ->whereHas('items') // Only orders with items
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $orders->map(fn ($order) => [
                'id' => $order->id,
                'order_no' => $order->order_no,
                'table' => $order->table,
                'items' => $order->items->map(fn ($item) => [
                    'id' => $item->id,
                    'menu_item_id' => $item->menu_item_id,
                    'name' => $item->menuItem->name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total' => $item->total,
                    'status' => $item->status ?? 'pending',
                    'image' => $item->menuItem->image_url,
                ]),
                'total_amount' => $order->invoice->total_amount ?? 0,
                'status' => $order->status,
                'created_at' => $order->created_at,
                'waiter' => $order->waiter,
            ]),
        ]);
    }

    /**
     * Update individual order item status
     */
    public function updateOrderItemStatus(Request $request, $itemId)
    {
        $request->validate([
            'status' => 'required|in:pending,served',
        ]);

        DB::beginTransaction();
        try {
            $orderItem = OrderItem::findOrFail($itemId);

            $oldStatus = $orderItem->status;

            $orderItem->update(['status' => $request->status]);
            $orderItem->kot->update(['status' => $request->status]);

            $order = $orderItem->order;
            $allItems = $order->items;

            // Update overall order status based on item statuses
            if ($allItems->every(fn ($item) => $item->status === 'served')) {
                $order->update(['status' => 'served']);
            } else {
                $order->update(['status' => 'confirmed']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Item status updated from {$oldStatus} to {$request->status}",
                'order_item' => $orderItem->load('menuItem'),
                'order_status' => $order->fresh()->status,
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update order item status: '.$e->getMessage(),
            ], 500);
        }
    }

    public function editTable(Table $table)
    {
        // Get active orders for this table
        $activeOrders = Order::with(['items.menuItem', 'waiter', 'kots.items.menuItem', 'invoice'])
            ->where('table_id', $table->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get all KOTs for this table's orders
        $allKots = Kot::with(['items.menuItem', 'order'])
            ->whereHas('order', function ($query) use ($table) {
                $query->where('table_id', $table->id)->where('status', '!=', \App\Enums\OrderStatusEnum::COMPLETED);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Get menu data for adding new items
        $menuItems = MenuItem::with('category')->get();
        $categories = Category::orderBy('name')->get();
        $waiters = User::get();

        // Backward-compat: pass legacy variable names for existing views
        $dishes = $menuItems;
        $menus = $categories;

        return view('admin.order.edit', compact(
            'table', 'activeOrders', 'allKots', 'menuItems', 'waiters',
            'dishes', 'menus', 'categories'
        ));
    }

    public function addItemsToTable(Request $request, Table $table)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.size' => 'nullable|numeric|min:0.5|max:1',
            'waiter_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            // Check if there are existing active orders for this table
            $existingOrder = Order::where('table_id', $table->id)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->first();

            if ($existingOrder) {
                // Add items to existing order
                $order = $existingOrder;
                $isNewOrder = false;
            } else {
                // Create new order only if no active orders exist
                $order = Order::create([
                    'order_no' => $this->generateOrderNumber(),
                    'table_id' => $table->id,
                    'waiter_id' => $request->waiter_id,
                    'entry_user_id' => Auth::id(),
                    'notes' => $request->notes,
                    'status' => 'pending',
                    'order_type' => 'dine_in',
                    'payment_status' => 'pending',
                ]);

                // Create initial invoice
                Invoice::create([
                    'order_id' => $order->id,
                    'invoice_number' => $this->generateInvoiceNumber(),
                    'subtotal' => 0,
                    'vat_percent' => 13.00,
                    'vat_amount' => 0,
                    'total_amount' => 0,
                    'payment_status' => 'pending',
                    'notes' => $request->notes,
                ]);
                $isNewOrder = true;
            }

            // Add new items to the order
            foreach ($request->items as $item) {
                $itemSize = $item['size'] ?? 1;
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item['menu_item_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['unit_price'] * $item['quantity'] * $itemSize,
                    'status' => 'pending', // Set default status
                    'is_kitchen_item' => true,
                    'size' => $itemSize,
                ]);
            }

            // Recalculate invoice totals
            $this->recalculateOrderTotals($order);

            // Create KOT for new items
            $kot = $this->createKOTForNewItems($order, $request->items);

            // Ensure table is marked as occupied
            $table->update(['status' => 'occupied']);
            $order->update(['status' => 'pending']);

            DB::commit();

            $order->load(['items.menuItem', 'table', 'waiter', 'invoice']);

            $message = $isNewOrder ? 'New order created successfully' : 'Items added to existing order successfully';

            return response()->json([
                'success' => true,
                'message' => $message,
                'order' => $order,
                'kot' => $kot,
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to add items: '.$e->getMessage(),
            ], 500);
        }
    }

    public function showCheckout(Table $table)
    {
        $this->authorize('checkout-view');

        $orders = Order::with(['items.menuItem', 'invoice', 'waiter'])
            ->where('table_id', $table->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->get();

        if ($orders->isEmpty()) {
            return redirect()->route('admin.orders.table.edit', $table)
                ->with('error', 'No active orders found for this table');
        }

        $subtotal = $orders->sum(fn($o) => $o->items->sum('total'));

        $serviceChargeAmount = 0;
        $vatPercent = 0;
        $vatAmount = 0;
        $grandTotal = $subtotal;

        $siteName = app(\App\Services\WebsiteSettingService::class)->get('site_name', 'Restaurant');
        $address = app(\App\Services\WebsiteSettingService::class)->get('address', '');
        $contactPhone = app(\App\Services\WebsiteSettingService::class)->get('phone', '');

        return view('admin.order.checkout-standalone', compact(
            'table',
            'orders',
            'subtotal',
            'serviceChargeAmount',
            'vatAmount',
            'vatPercent',
            'grandTotal',
            'siteName',
            'address',
            'contactPhone'
        ));
    }

    public function checkoutTable(Request $request, Table $table)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,card,digital_wallet,credit_bill',
            'tender_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'service_charge_amount' => 'required|numeric|min:0',
            'vat_percent' => 'required|numeric|min:0|max:100',
            'vat_amount' => 'required|numeric|min:0',
            'is_non_chargeable' => 'nullable|boolean',
        ]);

        DB::beginTransaction();
        try {
            // Get all active orders for this table
            $orders = Order::with(['items', 'invoice'])
                ->where('table_id', $table->id)
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->get();

            if ($orders->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active orders found for this table',
                ], 400);
            }

            $totalAmount = $request->total_amount;

            // If non-chargeable, set total to 0 and skip tender check
            $isNonChargeable = $request->boolean('is_non_chargeable', false);
            if ($isNonChargeable) {
                $totalAmount = 0;
            } else {
                if ($request->tender_amount < $totalAmount) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tender amount is insufficient',
                    ], 400);
                }
            }

            $changeAmount = round($request->tender_amount - $totalAmount, 2);

            // Update all orders and invoices
            foreach ($orders as $order) {
                $paymentStatus = $isNonChargeable ? 'non_chargeable' : 'paid';
                $order->update([
                    'status' => 'completed',
                    'payment_status' => $paymentStatus,
                ]);

                $invoiceData = [
                    'subtotal' => $request->subtotal,
                    'service_charge' => $request->service_charge_amount,
                    'vat_percent' => $request->vat_percent,
                    'vat_amount' => $request->vat_amount,
                    'total_amount' => $totalAmount,
                    'payment_status' => $paymentStatus,
                    'payment_method' => $request->payment_method,
                    'paid_amount' => $totalAmount,
                    'paid_at' => now(),
                    'tender_amount' => $request->tender_amount,
                    'change_amount' => $changeAmount,
                ];

                if ($isNonChargeable) {
                    $invoiceData['is_non_chargeable'] = true;
                    $invoiceData['notes'] = ($order->invoice->notes ?? '').' | Non-chargeable/Complimentary';
                }

                $order->invoice->update($invoiceData);

                // Deduct inventory based on recipe when order is completed
                $this->deductInventoryForOrder($order);
            }

            // Free up the table
            $table->update(['status' => 'available']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Checkout completed successfully',
                'change_amount' => $changeAmount,
            ]);

        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to complete checkout: '.$e->getMessage(),
            ], 500);
        }
    }

    private function recalculateOrderTotals(Order $order)
    {
        $subtotal = $order->items->sum('total');
        $vat_percent = 13.00;
        $vat_amount = round($subtotal * ($vat_percent / 100), 2);
        $total_amount = $subtotal + $vat_amount;

        $order->invoice->update([
            'subtotal' => $subtotal,
            'vat_amount' => $vat_amount,
            'total_amount' => $total_amount,
        ]);
    }

    private function createKOT(Order $order)
    {
        $date = now()->format('Ymd');
        $lastKot = Kot::where('kot_number', 'like', "KOT-{$date}%")
            ->orderBy('kot_number', 'desc')
            ->first();

        $newNumber = $lastKot ? (int) substr($lastKot->kot_number, -3) + 1 : 1;
        $kotNumber = "KOT-{$date}-".str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $kot = Kot::create([
            'order_id' => $order->id,
            'kot_number' => $kotNumber,
            'status' => 'sent',
            'sent_at' => now(),
            'remarks' => $order->notes,
        ]);

        // Update order items with KOT reference
        $order->items()->where('is_kitchen_item', true)->update([
            'kot_id' => $kot->id,
            'kot_printed_at' => now(),
        ]);

        // Update order status
        $order->update([
            'kot_sent_at' => now(),
            'status' => 'confirmed',
        ]);

        return $kot;
    }

    private function createKOTForNewItems(Order $order, $items)
    {
        $date = now()->format('Ymd');
        $lastKot = Kot::where('kot_number', 'like', "KOT-{$date}%")
            ->orderBy('kot_number', 'desc')
            ->first();

        $newNumber = $lastKot ? (int) substr($lastKot->kot_number, -3) + 1 : 1;
        $kotNumber = "KOT-{$date}-".str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $kot = Kot::create([
            'order_id' => $order->id,
            'kot_number' => $kotNumber,
            'status' => 'sent',
            'sent_at' => now(),
            'remarks' => 'Additional items for '.$order->table->name,
        ]);

        // Link new items to this KOT
        $newItemIds = collect($items)->pluck('menu_item_id');
        $order->items()
            ->whereIn('menu_item_id', $newItemIds)
            ->whereNull('kot_id')
            ->update([
                'kot_id' => $kot->id,
                'kot_printed_at' => now(),
            ]);

        return $kot->load('items.menuItem');
    }

    private function generateOrderNumber()
    {
        $date = now()->format('Ymd');
        $lastOrder = Order::where('order_no', 'like', "ORD-{$date}%")
            ->orderBy('order_no', 'desc')
            ->first();

        $newNumber = $lastOrder ? (int) substr($lastOrder->order_no, -4) + 1 : 1;

        return "ORD-{$date}-".str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    private function generateInvoiceNumber()
    {
        $date = now()->format('Ymd');
        $lastInvoice = Invoice::where('invoice_number', 'like', "INV-{$date}%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        $newNumber = $lastInvoice ? (int) substr($lastInvoice->invoice_number, -4) + 1 : 1;

        return "INV-{$date}-".str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Deduct inventory based on recipe for an order
     */
    private function deductInventoryForOrder(Order $order)
    {
        // Check if inventory has already been deducted for this order
        if ($this->inventoryService->hasOrderDeductedStock($order->id)) {
            return;
        }

        foreach ($order->items as $orderItem) {
            $menuItem = $orderItem->menuItem;
            $recipe = $menuItem->recipe;

            if ($recipe) {
                foreach ($recipe->items as $recipeItem) {
                    $quantityNeeded = $recipeItem->quantity * $orderItem->quantity;

                    try {
                        $this->inventoryService->createPOSConsumption(
                            $recipeItem->product_id,
                            $quantityNeeded,
                            $order->id,
                            [
                                'notes' => "Recipe deduction for {$menuItem->name} (Order #{$order->order_no})",
                                'unit_id' => $recipeItem->unit_id,
                            ]
                        );
                    } catch (\Exception $e) {
                        // Log error but continue with other items
                        \Log::error("Failed to deduct inventory for product {$recipeItem->product_id}: " . $e->getMessage());
                    }
                }
            }
        }
    }

    /**
     * Reverse inventory deduction for an order (for cancellation/refund)
     */
    private function reverseInventoryForOrder(Order $order)
    {
        $transactions = $this->inventoryService->getStockLedger(null, [
            'reference_type' => 'Order',
            'reference_id' => $order->id,
            'transaction_type' => 'pos_consumption',
        ]);

        foreach ($transactions as $transaction) {
            try {
                $this->inventoryService->reverseTransaction($transaction, "Order cancellation - Order #{$order->order_no}");
            } catch (\Exception $e) {
                \Log::error("Failed to reverse inventory transaction: " . $e->getMessage());
            }
        }
    }

    public function pos()
    {
        $tables = Table::orderBy('name')->get();
        $categories = Category::where('status', 'active')->orderBy('name')->get();
        $menuItems = MenuItem::with('category')->get();
        $waiters = User::get();
        $orders = Order::with(['waiter', 'table'])->latest()->get();

        // Backward-compat: pass legacy variable names for existing POS view
        $dishes = $menuItems;
        $menus = $categories;
        $menuCategories = $categories;

        return view('admin.order.pos', compact(
            'tables', 'menuItems', 'waiters', 'orders', 'categories',
            'dishes', 'menus', 'menuCategories'
        ));
    }

    public function getMenusForCategory($categoryId)
    {
        $menuItems = MenuItem::where('category_id', $categoryId)
            ->where('status', 'active')
            ->get(['id', 'name']);

        return response()->json([
            'success' => true,
            'menus' => $menuItems,
        ]);
    }

    public function storePOSOrder(StoreOrderRequest $request)
    {
        DB::beginTransaction();
        try {
            $subtotal = 0;
            foreach ($request->items as $item) {
                $subtotal += $item['unit_price'] * $item['quantity'];
            }

            $vat_percent = 0.00;
            $vat_amount = round($subtotal * ($vat_percent / 100), 2);
            $total_amount = $subtotal + $vat_amount;

            // Check if table has an existing active order (dine-in only)
            $existingOrder = null;
            if ($request->order_type === 'dine_in' && $request->table_id) {
                $existingOrder = Order::where('table_id', $request->table_id)
                    ->whereNotIn('status', ['completed', 'cancelled'])
                    ->first();
            }

            if ($existingOrder) {
                // Add items to existing order
                $order = $existingOrder;

                foreach ($request->items as $item) {
                    $itemSize = $item['size'] ?? 1;
                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_item_id' => $item['menu_item_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total' => $item['unit_price'] * $item['quantity'] * $itemSize,
                        'status' => 'pending',
                        'is_kitchen_item' => true,
                        'size' => $itemSize,
                    ]);
                }

                $order->update(['status' => 'pending']);
                $this->recalculateOrderTotals($order);
                $kot = $this->createKOTForNewItems($order, $request->items);

                $table = Table::findOrFail($request->table_id);
                $table->update(['status' => 'occupied']);
            } else {
                $orderNumber = $this->generateOrderNumber();

                $orderData = [
                    'order_no' => $orderNumber,
                    'entry_user_id' => Auth::id(),
                    'notes' => $request->notes,
                    'status' => 'pending',
                    'order_type' => $request->order_type,
                    'payment_status' => 'pending',
                ];

                if ($request->order_type === 'dine_in') {
                    $orderData['table_id'] = $request->table_id;
                    $orderData['waiter_id'] = $request->waiter_id ?? null;
                } else {
                    $orderData['customer_name'] = $request->customer_name;
                    $orderData['customer_phone'] = $request->customer_phone;
                    if ($request->order_type === 'delivery') {
                        $orderData['delivery_address'] = $request->delivery_address;
                    }
                }

                $order = Order::create($orderData);

                foreach ($request->items as $item) {
                    OrderItem::create([
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

                if ($request->order_type === 'dine_in') {
                    $table = Table::findOrFail($request->table_id);
                    $table->update(['status' => 'occupied']);
                }

                Invoice::create([
                    'order_id' => $order->id,
                    'invoice_number' => $this->generateInvoiceNumber(),
                    'subtotal' => $subtotal,
                    'vat_percent' => $vat_percent,
                    'vat_amount' => $vat_amount,
                    'total_amount' => $total_amount,
                    'payment_status' => 'pending',
                    'notes' => $request->notes,
                    'customer_name' => $request->customer_name ?? null,
                    'customer_phone' => $request->customer_phone ?? null,
                    'delivery_address' => $request->delivery_address ?? null,
                ]);

                $kot = $this->createKOT($order);
            }

            DB::commit();

            $order->load(['items.menuItem', 'table', 'waiter', 'invoice']);

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order' => $order,
                'kot' => $kot->load('items.menuItem'),
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: '.$e->getMessage(),
            ], 500);
        }
    }

    public function orderdetailtable(Request $request)
    {
        // Load orders with proper relationships
        $orders = Order::with(['table', 'items.menuItem'])
            ->whereDate('created_at', now()->toDateString())
            ->orderBy('created_at', 'desc')
            ->get();

        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::today()->startOfDay();

        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::today()->endOfDay();

        $todayOrders = Order::with(['table', 'items.menuItem'])
            ->withSum('items', 'total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->get();

        return view('admin.order.orderdetailtable', compact('todayOrders', 'startDate', 'endDate', 'orders'));
    }

    public function todayorders(Request $request)
    {
        $orders = Order::with(['table', 'items.menuItem'])
            ->whereDate('created_at', now()->toDateString())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.order.todaysorder', compact('orders'));
    }

    public function getTodayOrdersCount()
    {
        $count = Order::whereDate('created_at', today())->count();

        return response()->json(['success' => true, 'count' => $count]);
    }

    public function showQuickCheckout($id)
    {
        $this->authorize('checkout-view');

        $order = Order::with(['items.menuItem', 'waiter', 'invoice'])->findOrFail($id);

        $orders = collect([$order]);

        $subtotal = $order->items->sum('total');

        $serviceChargeAmount = 0;
        $vatPercent = 0;
        $vatAmount = 0;
        $grandTotal = $subtotal;

        $siteName = app(\App\Services\WebsiteSettingService::class)->get('site_name', 'Restaurant');
        $address = app(\App\Services\WebsiteSettingService::class)->get('address', '');
        $contactPhone = app(\App\Services\WebsiteSettingService::class)->get('phone', '');

        return view('admin.order.checkout-standalone', compact(
            'order',
            'orders',
            'subtotal',
            'serviceChargeAmount',
            'vatAmount',
            'vatPercent',
            'grandTotal',
            'siteName',
            'address',
            'contactPhone'
        ));
    }

    public function quickCheckout(Request $request, $id)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:cash,card,digital_wallet,credit_bill',
            'tender_amount' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'service_charge_amount' => 'nullable|numeric|min:0',
            'vat_percent' => 'nullable|numeric|min:0|max:100',
            'vat_amount' => 'nullable|numeric|min:0',
            'is_non_chargeable' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::findOrFail($id);

            $isNonChargeable = $request->boolean('is_non_chargeable', false);
            $paymentStatus = $isNonChargeable ? 'non_chargeable' : 'paid';
            $totalAmount = $isNonChargeable ? 0 : $validated['total_amount'];

            $order->invoice->update(
                [
                    'subtotal' => $validated['subtotal'],
                    'service_charge' => $validated['service_charge_amount'] ?? 0,
                    'vat_percent' => $validated['vat_percent'] ?? 0,
                    'vat_amount' => $validated['vat_amount'] ?? 0,
                    'total_amount' => $totalAmount,
                    'payment_method' => $validated['payment_method'],
                    'payment_status' => $paymentStatus,
                    'tender_amount' => $validated['tender_amount'],
                    'change_amount' => $validated['tender_amount'] - $totalAmount,
                    'paid_at' => now(),
                ]
            );

            // Update order status
            $order->update([
                'status' => 'completed',
                'payment_status' => $paymentStatus,
                'completed_at' => now(),
            ]);

            if ($isNonChargeable) {
                $order->notes = ($order->notes ? $order->notes."\n" : '')
                    .'['.now()->format('Y-m-d H:i').'] Marked as Non-chargeable/Complimentary';
                $order->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Checkout completed successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Checkout failed: '.$e->getMessage(),
            ], 500);
        }
    }

    public function cancelOrder($id)
    {
        DB::beginTransaction();
        try {
            $order = Order::with(['items.kot', 'invoice', 'table'])->findOrFail($id);

            if (in_array($order->status, ['completed', 'cancelled'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order is already '.$order->status.' and cannot be cancelled',
                ], 400);
            }

            $orderNo = $order->order_no;

            if ($order->table) {
                $order->table->update(['status' => 'available']);
            }

            // Delete the order entirely so the table gets a fresh order next time
            $order->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order #'.$orderNo.' cancelled and deleted successfully',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel order: '.$e->getMessage(),
            ], 500);
        }
    }

    public function cancelOrderItem(Request $request, $id)
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        DB::beginTransaction();
        try {
            $orderItem = OrderItem::with(['order.table', 'kot'])->findOrFail($id);

            if ($orderItem->status === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Item is already cancelled',
                ], 400);
            }

            $oldStatus = $orderItem->status;
            $cancelNote = '['.now()->format('Y-m-d H:i').'] Cancelled by '.Auth::user()->name
                .($request->reason ? ' ('.$request->reason.')' : '');

            $orderItem->update([
                'status' => 'cancelled',
                'notes' => ($orderItem->notes ? $orderItem->notes."\n" : '').$cancelNote,
            ]);

            $order = $orderItem->order;

            if ($orderItem->kot_id) {
                $kot = $orderItem->kot;
                $remainingActive = $kot->items()
                    ->where('id', '!=', $id)
                    ->where('status', '!=', 'cancelled')
                    ->count();

                if ($remainingActive === 0) {
                    $kot->update(['status' => 'cancelled', 'remarks' => 'All items cancelled']);
                } else {
                    $kot->items()->where('id', $id)->update(['status' => 'cancelled']);
                }
            }

            $allItems = $order->items()->get();
            $activeCount = $allItems->where('status', '!=', 'cancelled')->count();

            if ($activeCount === 0) {
                // All items cancelled — delete the entire order so table gets a fresh start
                $orderNo = $order->order_no;

                if ($order->table) {
                    $order->table->update(['status' => 'available']);
                }

                $order->delete();

                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'Order #'.$orderNo.' cancelled and removed',
                ]);
            } else {
                $cancelledTotal = $allItems->where('status', 'cancelled')->sum('total');

                if ($order->invoice) {
                    $order->invoice->decrement('subtotal', $cancelledTotal);
                    $order->invoice->decrement('total_amount', $cancelledTotal);
                }

                if ($allItems->where('status', 'served')->count() === $activeCount) {
                    $order->update(['status' => 'served']);
                } elseif ($allItems->whereIn('status', ['served', 'ready'])->count() === $activeCount) {
                    $order->update(['status' => 'confirmed']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item cancelled successfully',
                'order_status' => $order->fresh()->status,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel item: '.$e->getMessage(),
            ], 500);
        }
    }

    public function getDeliveryOrders(Request $request)
    {
        try {
            $orders = Order::with(['items'])
                ->whereIn('order_type', ['delivery'])
                ->whereDate('created_at', today())
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($order) {
                    // Calculate total from items
                    $itemsTotal = $order->items->sum('total');

                    return [
                        'id' => $order->id,
                        'order_no' => $order->order_no,
                        'customer_name' => $order->customer_name,
                        'customer_phone' => $order->customer_phone,
                        'delivery_address' => $order->delivery_address,
                        'payment_status' => $order->payment_status,
                        'status' => $order->status->value ?? $order->status,
                        'delivery_status' => $order->delivery_status,
                        'order_type' => $order->order_type,
                        'order_items_count' => $order->items->count(),
                        'total' => $itemsTotal,
                        'created_at' => $order->created_at->toISOString(),
                    ];
                });

            return response()->json([
                'success' => true,
                'orders' => $orders,
            ]);
        } catch (\Exception $e) {
            \Log::error('Delivery Orders Error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load delivery orders',
            ], 500);
        }
    }

    // Add this method to your OrderController

    public function getOrderDetails($id)
    {
        try {
            $order = Order::with([
                'items.menuItem',
                'table',
                'waiter',
                'entryUser',
            ])
                ->findOrFail($id);

            // Transform the data
            $orderData = [
                'id' => $order->id,
                'order_no' => $order->order_no,
                'order_type' => $order->order_type,
                'status' => is_object($order->status) ? $order->status->value : $order->status,
                'payment_status' => $order->payment_status,
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'delivery_address' => $order->delivery_address,
                'notes' => $order->notes,
                'no_of_guests' => $order->no_of_guests,
                'vat_percent' => $order->vat_percent ?? 13,
                'paid_amount' => $order->paid_amount ?? 0,
                'created_at' => $order->created_at->toISOString(),
                'table' => $order->table ? [
                    'id' => $order->table->id,
                    'name' => $order->table->name,
                ] : null,
                'waiter' => $order->waiter ? [
                    'id' => $order->waiter->id,
                    'name' => $order->waiter->name,
                ] : null,
                'items' => $order->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'quantity' => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total' => $item->total,
                        'size' => $item->size ?? 1,
                        'notes' => $item->notes,
                        'menuItem' => $item->menuItem ? [
                            'id' => $item->menuItem->id,
                            'name' => $item->menuItem->name,
                            'image_url' => $item->menuItem->image_url,
                        ] : null,
                    ];
                }),
            ];

            return response()->json([
                'success' => true,
                'order' => $orderData,
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Order Details Error: '.$e->getMessage(), [
                'order_id' => $id,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load order details',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error',
            ], 500);
        }
    }

    // In your OrderController.php

    public function show($id)
    {
        try {
            $order = Order::with(['items.menuItem', 'table', 'waiter', 'invoice'])
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'order' => [
                    'id' => $order->id,
                    'order_no' => $order->order_no,
                    'customer_name' => $order->customer_name,
                    'customer_phone' => $order->customer_phone,
                    'delivery_address' => $order->delivery_address,
                    'delivery_status' => $order->delivery_status,
                    'order_type' => $order->order_type,
                    'status' => $order->status,
                    'payment_status' => $order->payment_status,
                    'total' => $order->invoice->total_amount ?? 0,
                    'created_at' => $order->created_at,
                ],
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found',
            ], 404);
        }
    }

    public function updateDeliveryStatus(Request $request, $id)
    {
        $request->validate([
            'delivery_status' => 'required|in:pending,on the way,delivered,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            $order = Order::findOrFail($id);

            $order->update([
                'delivery_status' => $request->delivery_status,
            ]);

            // Optionally, you can log the status change or add to order notes
            if ($request->notes) {
                $order->update([
                    'notes' => $order->notes."\n[".now()->format('Y-m-d H:i').'] Status changed to: '.$request->delivery_status.' - '.$request->notes,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Delivery status updated successfully',
                'order' => $order,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update delivery status: '.$e->getMessage(),
            ], 500);
        }
    }
}
