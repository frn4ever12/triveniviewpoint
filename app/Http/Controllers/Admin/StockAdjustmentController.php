<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockAdjustment;
use App\Models\StockAdjustmentItem;
use App\Models\Product;
use App\Services\InventoryTransactionService;
use Illuminate\Http\Request;

class StockAdjustmentController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryTransactionService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index()
    {
        $adjustments = StockAdjustment::with(['items.product', 'user'])->get();
        return view('admin.stock-adjustments.index', compact('adjustments'));
    }

    public function create()
    {
        $products = Product::active()->get();
        return view('admin.stock-adjustments.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'adjustment_date' => 'required|date',
            'adjustment_type' => 'required|in:increase,decrease',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_id' => 'nullable|exists:units,id',
        ]);

        $adjustment = StockAdjustment::create([
            'adjustment_date' => $validated['adjustment_date'],
            'tenant_id' => auth()->user()->tenant_id,
            'user_id' => auth()->id(),
            'adjustment_type' => $validated['adjustment_type'],
            'reason' => $validated['reason'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $totalCost = 0;

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $unitCost = $product->average_cost ?? $product->purchase_cost ?? 0;
            $totalValue = $item['quantity'] * $unitCost;
            $totalCost += $totalValue;

            StockAdjustmentItem::create([
                'stock_adjustment_id' => $adjustment->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_id' => $item['unit_id'] ?? null,
                'unit_cost' => $unitCost,
                'total_value' => $totalValue,
            ]);

            // Create inventory transaction
            $this->inventoryService->createStockAdjustment(
                $item['product_id'],
                $item['quantity'],
                $validated['adjustment_type'],
                $adjustment->id,
                ['notes' => $validated['reason']]
            );
        }

        $adjustment->update(['total_cost' => $totalCost]);

        return redirect()->route('admin.stock-adjustments.show', $adjustment->id)
            ->with('success', 'Stock adjustment created successfully.');
    }

    public function show(StockAdjustment $adjustment)
    {
        $adjustment->load(['items.product', 'items.unit', 'user']);
        return view('admin.stock-adjustments.show', compact('adjustment'));
    }

    public function destroy(StockAdjustment $adjustment)
    {
        $adjustment->delete();
        return redirect()->route('admin.stock-adjustments.index')
            ->with('success', 'Stock adjustment deleted successfully.');
    }
}
