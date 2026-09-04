<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Vinkla\Hashids\Facades\Hashids;

class CheckoutController extends Controller
{
    /**
     * Process checkout and create order
     */
    public function process(Request $request, $table = null)
    {
        $tableRecord = null;

        // Validate table if provided
        if ($table) {
            try {
                $tableId = Hashids::decode($table);
                if (empty($tableId)) {
                    abort(404);
                }
                $tableRecord = \App\Models\Table::findOrFail($tableId[0]);
            } catch (\Exception $e) {
                abort(404);
            }
        }

        // Validation rules based on whether table exists
        $rules = [
            'order_notes' => 'nullable|string|max:1000',
        ];

        if (! $tableRecord) {
            $rules['customer_name'] = 'required|string|max:255';
            $rules['customer_phone'] = 'required|string|max:20';
            $rules['customer_address'] = 'required|string';
        }

        $validated = $request->validate($rules);

        // Get cart from session
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect('/cart')
                ->with('error', 'Your cart is empty. Please add items before checkout.');
        }

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            $cartItems = [];

            foreach ($cart as $itemId => $item) {
                $menuItem = MenuItem::find($itemId);

                if (! $menuItem) {
                    continue;
                }

                $quantity = is_array($item) ? ($item['quantity'] ?? 1) : $item;
                $itemTotal = $menuItem->price * $quantity;

                $cartItems[] = [
                    'menuItem' => $menuItem,
                    'quantity' => $quantity,
                    'unit_price' => $menuItem->price,
                    'total' => $itemTotal,
                ];

                $subtotal += $itemTotal;
            }

            $vatPercent = 0;
            $vatAmount = 0;
            $serviceCharge = 0;
            $totalAmount = $subtotal;

            $orderNo = $this->generateOrderNumber();

            // Create Order
            $order = Order::create([
                'order_no' => $orderNo,
                'order_type' => $tableRecord ? 'dine_in' : 'delivery',
                'status' => 'pending',
                'payment_status' => 'pending',
                'customer_name' => $validated['customer_name'] ?? null,
                'customer_phone' => $validated['customer_phone'] ?? null,
                'delivery_address' => $validated['customer_address'] ?? null,
                'notes' => $validated['order_notes'] ?? null,
                'table_id' => $tableRecord ? $tableRecord->id : null,
                'waiter_id' => null,
                'entry_user_id' => auth()->id(),
                'no_of_guests' => null,
            ]);

            // Create Order Items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $item['menuItem']->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['total'],
                    'status' => 'pending',
                    'is_kitchen_item' => true,
                ]);
            }

            $invoiceNumber = $this->generateInvoiceNumber();

            // Create Invoice
            $invoice = Invoice::create([
                'order_id' => $order->id,
                'invoice_number' => $invoiceNumber,
                'customer_name' => $validated['customer_name'] ?? 'Table '.$tableRecord?->name,
                'customer_phone' => $validated['customer_phone'] ?? null,
                'delivery_address' => $validated['customer_address'] ?? null,
                'subtotal' => $subtotal,
                'vat_percent' => $vatPercent,
                'vat_amount' => $vatAmount,
                'service_charge' => $serviceCharge,
                'discount_amount' => 0,
                'total_amount' => $totalAmount,
                'payment_status' => 'pending',
                'payment_method' => null,
                'paid_amount' => 0,
            ]);

            DB::commit();

            session()->forget('cart');

            // Redirect based on order type
            if ($tableRecord) {
                $tableRecord->update(['status' => 'occupied']);

                return redirect()->route('order.confirmation', [
                    'order' => $order->id,
                    'table' => $table,
                ])->with('success', 'Order placed successfully! Order #'.$order->order_no);
            }

            return redirect('/')
                ->with('success', 'Order confirmed! Your order #'.$order->order_no.' has been placed successfully. We will contact you at '.$order->customer_phone.' shortly.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect('/cart')
                ->with('error', 'Failed to place order. Please try again. Error: '.$e->getMessage());
        }
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber()
    {
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(4));

        // Format: ORD-20250111-ABCD
        return "ORD-{$date}-{$random}";
    }

    /**
     * Generate unique invoice number
     */
    private function generateInvoiceNumber()
    {
        $date = now()->format('Ymd');

        // Get today's invoice count
        $count = Invoice::whereDate('created_at', now()->toDateString())->count() + 1;

        // Format: INV-20250111-001
        return sprintf('INV-%s-%03d', $date, $count);
    }

    /**
     * Show order confirmation page
     */
    public function confirmation(Order $order)
    {
        // Load relationships
        $order->load(['items.menuItem', 'invoice']);

        return redirect('/')
            ->with('success', 'Order confirmed! Your order #'.$order->order_no.' has been placed successfully. We will get your order shortly.');

    }
}
