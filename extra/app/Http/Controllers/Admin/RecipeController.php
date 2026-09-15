<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Models\RecipeItem;
use App\Models\MenuItem;
use App\Models\Product;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index()
    {
        $recipes = Recipe::with(['menuItem', 'items.product'])->get();
        return view('admin.recipes.index', compact('recipes'));
    }

    public function create()
    {
        $menuItems = MenuItem::all();
        $products = Product::active()->get();
        return view('admin.recipes.create', compact('menuItems', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_item_id' => 'required|exists:menu_items,id',
            'preparation_time' => 'nullable|integer',
            'instructions' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_id' => 'nullable|exists:units,id',
            'items.*.wastage_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $recipe = Recipe::create([
            'menu_item_id' => $validated['menu_item_id'],
            'tenant_id' => auth()->user()->tenant_id,
            'preparation_time' => $validated['preparation_time'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'selling_price' => MenuItem::find($validated['menu_item_id'])->price ?? 0,
        ]);

        foreach ($validated['items'] as $item) {
            $recipeItem = RecipeItem::create([
                'recipe_id' => $recipe->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_id' => $item['unit_id'] ?? null,
                'wastage_percent' => $item['wastage_percent'] ?? 0,
            ]);

            $recipeItem->updateCostFromProduct();
        }

        $recipe->calculateRecipeCost();
        $recipe->calculateFoodCostPercent();

        return redirect()->route('admin.recipes.show', $recipe->id)
            ->with('success', 'Recipe created successfully.');
    }

    public function show(Recipe $recipe)
    {
        $recipe->load(['menuItem', 'items.product', 'items.unit']);
        return view('admin.recipes.show', compact('recipe'));
    }

    public function edit(Recipe $recipe)
    {
        $recipe->load(['items']);
        $products = Product::active()->get();
        return view('admin.recipes.edit', compact('recipe', 'products'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        $validated = $request->validate([
            'preparation_time' => 'nullable|integer',
            'instructions' => 'nullable|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0',
            'items.*.unit_id' => 'nullable|exists:units,id',
            'items.*.wastage_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $recipe->update([
            'preparation_time' => $validated['preparation_time'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
        ]);

        // Delete existing items
        $recipe->items()->delete();

        // Create new items
        foreach ($validated['items'] as $item) {
            $recipeItem = RecipeItem::create([
                'recipe_id' => $recipe->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_id' => $item['unit_id'] ?? null,
                'wastage_percent' => $item['wastage_percent'] ?? 0,
            ]);

            $recipeItem->updateCostFromProduct();
        }

        $recipe->calculateRecipeCost();
        $recipe->calculateFoodCostPercent();

        return redirect()->route('admin.recipes.show', $recipe->id)
            ->with('success', 'Recipe updated successfully.');
    }

    public function destroy(Recipe $recipe)
    {
        $recipe->delete();
        return redirect()->route('admin.recipes.index')
            ->with('success', 'Recipe deleted successfully.');
    }

    public function updateCosts(Recipe $recipe)
    {
        foreach ($recipe->items as $item) {
            $item->updateCostFromProduct();
        }

        $recipe->calculateRecipeCost();
        $recipe->calculateFoodCostPercent();

        return redirect()->route('admin.recipes.show', $recipe->id)
            ->with('success', 'Recipe costs updated successfully.');
    }
}
