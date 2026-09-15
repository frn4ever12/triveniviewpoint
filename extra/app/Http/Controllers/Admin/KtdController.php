<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KtdController extends Controller
{
    public function index()
    {
        $orders = Order::with(['items.menuItem', 'table'])
            ->whereIn('status', ['pending', 'preparing', 'confirmed', 'served'])
            ->orderBy('created_at', 'asc')
            ->get();

        $statusCounts = [
            'pending' => $orders->where('status', 'pending')->count(),
            'preparing' => $orders->where('status', 'preparing')->count(),
            'confirmed' => $orders->where('status', 'confirmed')->count(),
        ];

        return view('admin.ktd.index', compact('orders', 'statusCounts'));
    }

    /**
     * Batch update all items of an order to a given status (prepare/ready/served).
     */
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:preparing,ready,served',
        ]);

        $status = $request->status;

        DB::beginTransaction();
        try {
            // Update all non-cancelled items to the new status
            $order->items()
                ->where('status', '!=', 'cancelled')
                ->update(['status' => $status]);

            // Update the KOT status if all items share the same status
            foreach ($order->kots as $kot) {
                $kotItems = $kot->items()->where('status', '!=', 'cancelled');
                $kotItems->update(['status' => $status]);

                $remainingActive = $kot->items()
                    ->where('status', '!=', 'cancelled')
                    ->count();

                if ($remainingActive === 0) {
                    $kot->update(['status' => 'cancelled']);
                } else {
                    $allSame = $kot->items()
                        ->where('status', '!=', 'cancelled')
                        ->where('status', '!=', $status)
                        ->count() === 0;
                    $kot->update(['status' => $allSame ? $status : 'partial']);
                }
            }

            // Update overall order status
            $order->update(['status' => $status]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order status updated to ' . ucfirst($status),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to update order status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
