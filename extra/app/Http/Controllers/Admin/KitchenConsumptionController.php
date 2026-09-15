<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KitchenConsumption;
use App\Models\KitchenConsumptionItem;
use App\Models\Product;
use App\Services\InventoryTransactionService;
use Illuminate\Http\Request;

class KitchenConsumptionController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryTransactionService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index()
    {
        $consumptions = KitchenConsumption::with(['items.product', 'user'])->get();
        return view('admin.kitchen-consumptions.index', compact('consumptions'));
    }

    public function create()
    {
        $products = Product::active()->get();
        return view('admin.kitchen-consumptions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'consumption_date' => 'required|date',
            'kitchen_department' => 'nullable|string',
            'reason' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_id' => 'nullable|exists:units,id',
        ]);

        $consumption = KitchenConsumption::create([
            'consumption_date' => $validated['consumption_date'],
            'tenant_id' => auth()->user()->tenant_id,
            'user_id' => auth()->id(),
            'kitchen_department' => $validated['kitchen_department'] ?? null,
            'reason' => $validated['reason'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        $totalCost = 0;

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $unitCost = $product->average_cost ?? $product->purchase_cost ?? 0;
            $totalValue = $item['quantity'] * $unitCost;
            $totalCost += $totalValue;

            KitchenConsumptionItem::create([
                'kitchen_consumption_id' => $consumption->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_id' => $item['unit_id'] ?? null,
                'unit_cost' => $unitCost,
                'total_cost' => $totalValue,
            ]);

            // Create inventory transaction
            $this->inventoryService->createKitchenConsumption(
                $item['product_id'],
                $item['quantity'],
                $consumption->id,
                ['notes' => $validated['reason'] ?? 'Kitchen consumption']
            );
        }

        $consumption->update(['total_cost' => $totalCost]);

        return redirect()->route('admin.kitchen-consumptions.show', $consumption->id)
            ->with('success', 'Kitchen consumption recorded successfully.');
    }

    public function show(KitchenConsumption $consumption)
    {
        $consumption->load(['items.product', 'items.unit', 'user']);
        return view('admin.kitchen-consumptions.show', compact('consumption'));
    }

    public function destroy(KitchenConsumption $consumption)
    {
        $consumption->delete();
        return redirect()->route('admin.kitchen-consumptions.index')
            ->with('success', 'Kitchen consumption deleted successfully.');
    }
}
