<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wastage;
use App\Models\WastageItem;
use App\Models\Product;
use App\Services\InventoryTransactionService;
use Illuminate\Http\Request;

class WastageController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryTransactionService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index()
    {
        $wastages = Wastage::with(['items.product', 'user'])->get();
        return view('admin.wastages.index', compact('wastages'));
    }

    public function create()
    {
        $products = Product::active()->get();
        return view('admin.wastages.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'wastage_date' => 'required|date',
            'reason' => 'required|in:expired,spoiled,damaged,burnt,overproduction,preparation_waste,kitchen_waste,other',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_id' => 'nullable|exists:units,id',
            'items.*.batch_number' => 'nullable|string',
        ]);

        $wastage = Wastage::create([
            'wastage_date' => $validated['wastage_date'],
            'tenant_id' => auth()->user()->tenant_id,
            'user_id' => auth()->id(),
            'reason' => $validated['reason'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $totalCost = 0;

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $unitCost = $product->average_cost ?? $product->purchase_cost ?? 0;
            $totalValue = $item['quantity'] * $unitCost;
            $totalCost += $totalValue;

            WastageItem::create([
                'wastage_id' => $wastage->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_id' => $item['unit_id'] ?? null,
                'batch_number' => $item['batch_number'] ?? null,
                'unit_cost' => $unitCost,
                'total_cost' => $totalValue,
            ]);

            // Create inventory transaction
            $this->inventoryService->createWastage(
                $item['product_id'],
                $item['quantity'],
                $wastage->id,
                ['batch_number' => $item['batch_number'] ?? null, 'notes' => $validated['reason']]
            );
        }

        $wastage->update(['total_cost' => $totalCost]);

        return redirect()->route('admin.wastages.show', $wastage->id)
            ->with('success', 'Wastage recorded successfully.');
    }

    public function show(Wastage $wastage)
    {
        $wastage->load(['items.product', 'items.unit', 'user']);
        return view('admin.wastages.show', compact('wastage'));
    }

    public function destroy(Wastage $wastage)
    {
        $wastage->delete();
        return redirect()->route('admin.wastages.index')
            ->with('success', 'Wastage deleted successfully.');
    }
}
