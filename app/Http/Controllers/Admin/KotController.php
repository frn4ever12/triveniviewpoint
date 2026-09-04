<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KOT;
use App\Models\KOTItem;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class KotController extends Controller
{
    /**
     * Display all KOTs
     */
    public function index()
    {
        $kots = KOT::with(['order.table', 'items.dish'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.kot.index', compact('kots'));
    }

    /**
     * Show KOT details
     */
    public function show(KOT $kot)
    {
        $kot->load(['order.table', 'order.waiter', 'items.dish', 'items.orderItem']);
        
        return view('admin.kot.show', compact('kot'));
    }

    /**
     * Reprint a KOT
     */
    public function reprintKOT(Request $request, $kotId)
    {
        try {
            $kot = KOT::with(['items.dish', 'order.table'])->findOrFail($kotId);
            
            // Track reprint in remarks
            $currentRemarks = $kot->remarks ? $kot->remarks . ' | ' : '';
            $kot->update([
                'remarks' => $currentRemarks . 'Reprinted at: ' . now()->format('Y-m-d H:i:s')
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'KOT reprinted successfully',
                'kot' => [
                    'id' => $kot->id,
                    'kot_number' => $kot->kot_number,
                    'items' => $kot->items->map(function($item) {
                        return [
                            'quantity' => $item->quantity,
                            'name' => $item->dish->name ?? 'Unknown Item',
                            'dish' => [
                                'name' => $item->dish->name ?? 'Unknown Item'
                            ]
                        ];
                    }),
                    'table_name' => $kot->order->table->name ?? 'Unknown Table',
                    'created_at' => $kot->created_at->format('Y-m-d H:i:s'),
                    'status' => $kot->status
                ]
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reprint KOT: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update KOT status
     */
    public function updateKOTStatus(Request $request, $kotId)
    {
        $request->validate([
            'status' => 'required|in:pending,sent,preparing,ready,served,cancelled'
        ]);

        DB::beginTransaction();
        try {
            $kot = KOT::with(['items.orderItem', 'order'])->findOrFail($kotId);
            $oldStatus = $kot->status;
            
            $kot->update(['status' => $request->status]);
            
            // If marking as sent, update sent_at timestamp
            if ($request->status === 'sent' && !$kot->sent_at) {
                $kot->update(['sent_at' => now()]);
            }
            
            // Update related order item statuses based on KOT status
            if (in_array($request->status, ['preparing', 'ready', 'served'])) {
                $newItemStatus = match($request->status) {
                    'preparing' => 'preparing',
                    'ready' => 'ready', 
                    'served' => 'served',
                    default => 'pending'
                };
                
                foreach ($kot->items as $kotItem) {
                    if ($kotItem->orderItem) {
                        $kotItem->orderItem->update(['status' => $newItemStatus]);
                    }
                }
                
                // Update overall order status
                $this->updateOrderStatus($kot->order);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "KOT status updated from {$oldStatus} to {$request->status}",
                'kot' => $kot->fresh(['items.dish', 'order.table']),
                'order_status' => $kot->order->fresh()->status,
            ]);
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update KOT status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle individual order item status (for KOT items)
     */
    public function toggleOrderItemStatus(Request $request, $itemId)
    {
        DB::beginTransaction();
        try {
            $orderItem = OrderItem::with(['dish', 'order.table', 'kot'])->findOrFail($itemId);
            
            // Define status progression: pending -> preparing -> ready -> served
            $statusProgression = [
                'pending' => 'preparing',
                'preparing' => 'ready',
                'ready' => 'served',
                'served' => 'served' // Can't go beyond served
            ];
            
            $currentStatus = $orderItem->status ?? 'pending';
            $newStatus = $statusProgression[$currentStatus] ?? 'preparing';
            
            $orderItem->update(['status' => $newStatus]);
            
            // Update KOT status based on all its items
            if ($orderItem->kot) {
                $this->updateKOTStatusBasedOnItems($orderItem->kot);
            }
            
            // Update overall order status
            $this->updateOrderStatus($orderItem->order);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Item status updated from {$currentStatus} to {$newStatus}",
                'order_item' => $orderItem->fresh(['dish']),
                'kot_status' => $orderItem->kot ? $orderItem->kot->fresh()->status : null,
                'order_status' => $orderItem->order->fresh()->status,
            ]);
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to toggle item status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Set specific status for order item
     */
    public function setOrderItemStatus(Request $request, $itemId)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,served'
        ]);

        DB::beginTransaction();
        try {
            $orderItem = OrderItem::with(['dish', 'order.table', 'kot'])->findOrFail($itemId);
            $oldStatus = $orderItem->status ?? 'pending';
            
            $orderItem->update(['status' => $request->status]);
            
            // Update KOT status based on all its items
            if ($orderItem->kot) {
                $this->updateKOTStatusBasedOnItems($orderItem->kot);
            }
            
            // Update overall order status
            $this->updateOrderStatus($orderItem->order);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => "Item status updated from {$oldStatus} to {$request->status}",
                'order_item' => $orderItem->fresh(['dish']),
                'kot_status' => $orderItem->kot ? $orderItem->kot->fresh()->status : null,
                'order_status' => $orderItem->order->fresh()->status,
            ]);
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update item status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark all items in KOT as ready
     */
    public function markKOTReady(Request $request, $kotId)
    {
        DB::beginTransaction();
        try {
            $kot = KOT::with(['items.orderItem', 'order'])->findOrFail($kotId);
            
            // Update all order items to ready status
            foreach ($kot->items as $kotItem) {
                if ($kotItem->orderItem && $kotItem->orderItem->status !== 'served') {
                    $kotItem->orderItem->update(['status' => 'ready']);
                }
            }
            
            // Update KOT status to ready
            $kot->update(['status' => 'ready']);
            
            // Update overall order status
            $this->updateOrderStatus($kot->order);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'All items marked as ready',
                'kot' => $kot->fresh(['items.dish', 'items.orderItem']),
                'order_status' => $kot->order->fresh()->status,
            ]);
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark KOT as ready: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Mark all items in KOT as served
     */
    public function markKOTServed(Request $request, $kotId)
    {
        DB::beginTransaction();
        try {
            $kot = KOT::with(['items.orderItem', 'order'])->findOrFail($kotId);
            
            // Update all order items to served status
            foreach ($kot->items as $kotItem) {
                if ($kotItem->orderItem) {
                    $kotItem->orderItem->update(['status' => 'served']);
                }
            }
            
            // Update KOT status to served
            $kot->update(['status' => 'served']);
            
            // Update overall order status
            $this->updateOrderStatus($kot->order);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'All items marked as served',
                'kot' => $kot->fresh(['items.dish', 'items.orderItem']),
                'order_status' => $kot->order->fresh()->status,
            ]);
            
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark KOT as served: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get KOT details for printing
     */
    public function getKOTForPrint($kotId)
    {
        try {
            $kot = KOT::with(['items.dish', 'order.table', 'order.waiter'])->findOrFail($kotId);
            
            return response()->json([
                'success' => true,
                'kot' => [
                    'id' => $kot->id,
                    'kot_number' => $kot->kot_number,
                    'status' => $kot->status,
                    'created_at' => $kot->created_at,
                    'sent_at' => $kot->sent_at,
                    'items' => $kot->items->map(function($item) {
                        return [
                            'id' => $item->id,
                            'quantity' => $item->quantity,
                            'dish' => [
                                'name' => $item->dish->name ?? 'Unknown Item',
                                'category' => $item->dish->menu->name ?? 'Unknown Category'
                            ],
                            'notes' => $item->notes,
                            'status' => $item->orderItem ? $item->orderItem->status : 'pending'
                        ];
                    }),
                    'order' => [
                        'table' => $kot->order->table->name ?? 'Unknown Table',
                        'waiter' => $kot->order->waiter->name ?? 'No Waiter',
                        'notes' => $kot->order->notes
                    ]
                ]
            ]);
            
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get KOT details: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update KOT status based on its items
     */
    private function updateKOTStatusBasedOnItems($kot)
    {
        $items = $kot->items()->with('orderItem')->get();
        
        if ($items->isEmpty()) {
            return;
        }
        
        $orderItems = $items->pluck('orderItem')->filter();
        
        if ($orderItems->every(fn($item) => $item->status === 'served')) {
            $kot->update(['status' => 'served']);
        } elseif ($orderItems->every(fn($item) => $item->status === 'ready')) {
            $kot->update(['status' => 'ready']);
        } elseif ($orderItems->some(fn($item) => in_array($item->status, ['preparing', 'ready']))) {
            $kot->update(['status' => 'preparing']);
        } else {
            $kot->update(['status' => 'sent']);
        }
    }

    /**
     * Update order status based on all order items
     */
    private function updateOrderStatus($order)
    {
        $allItems = $order->items;
        
        if ($allItems->every(fn($item) => $item->status === 'served')) {
            $order->update(['status' => 'served']);
        } elseif ($allItems->every(fn($item) => $item->status === 'ready')) {
            $order->update(['status' => 'ready']);
        } elseif ($allItems->some(fn($item) => in_array($item->status, ['preparing', 'ready']))) {
            $order->update(['status' => 'preparing']);
        } else {
            $order->update(['status' => 'confirmed']);
        }
    }
}