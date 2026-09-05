<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\RecipeItem;
use App\Models\MenuItem;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        // Get units
        $kg = Unit::where('name', 'Kilogram')->first();
        $litre = Unit::where('name', 'Litre')->first();
        $piece = Unit::where('name', 'Piece')->first();

        // Get menu items
        $steamMomoChicken = MenuItem::where('slug', 'steam-momo-chicken')->first();
        $steamMomoVeg = MenuItem::where('slug', 'steam-momo-veg')->first();
        $vegChowmein = MenuItem::where('slug', 'veg-chowmein')->first();
        $chickenChowmein = MenuItem::where('slug', 'chicken-chowmein')->first();
        $alooSamosa = MenuItem::where('slug', 'aloo-samosa')->first();
        $vegPakauda = MenuItem::where('slug', 'veg-pakauda')->first();
        $nepaliThaliVeg = MenuItem::where('slug', 'nepali-thali-veg')->first();
        $nepaliThaliChicken = MenuItem::where('slug', 'nepali-thali-chicken')->first();

        // Get products
        $maidaFlour = Product::where('sku', 'FLOUR-001')->first();
        $attaFlour = Product::where('sku', 'FLOUR-002')->first();
        $onion = Product::where('sku', 'VEG-001')->first();
        $potato = Product::where('sku', 'VEG-002')->first();
        $tomato = Product::where('sku', 'VEG-003')->first();
        $garlic = Product::where('sku', 'VEG-004')->first();
        $ginger = Product::where('sku', 'VEG-005')->first();
        $greenChilli = Product::where('sku', 'VEG-006')->first();
        $coriander = Product::where('sku', 'VEG-007')->first();
        $cumin = Product::where('sku', 'SPC-001')->first();
        $turmeric = Product::where('sku', 'SPC-002')->first();
        $redChilli = Product::where('sku', 'SPC-003')->first();
        $garamMasala = Product::where('sku', 'SPC-004')->first();
        $salt = Product::where('sku', 'SPC-005')->first();
        $chicken = Product::where('sku', 'MEAT-001')->first();
        $vegetableOil = Product::where('sku', 'OIL-001')->first();
        $butter = Product::where('sku', 'DAIRY-002')->first();
        $paneer = Product::where('sku', 'DAIRY-003')->first();

        $recipes = [
            // Steam Momo (Chicken) - Recipe for 10 pieces
            [
                'menu_item' => $steamMomoChicken,
                'preparation_time' => 15,
                'instructions' => 'Mix flour with water to make dough. Prepare chicken filling with minced chicken, onion, garlic, ginger, and spices. Stuff dough balls with filling and steam for 15 minutes.',
                'items' => [
                    ['product' => $attaFlour, 'quantity' => 0.3, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $chicken, 'quantity' => 0.25, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $onion, 'quantity' => 0.1, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $garlic, 'quantity' => 0.02, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $ginger, 'quantity' => 0.02, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $greenChilli, 'quantity' => 0.02, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $salt, 'quantity' => 0.01, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $vegetableOil, 'quantity' => 0.02, 'unit' => $litre, 'wastage' => 5],
                ],
            ],

            // Steam Momo (Veg) - Recipe for 10 pieces
            [
                'menu_item' => $steamMomoVeg,
                'preparation_time' => 15,
                'instructions' => 'Mix flour with water to make dough. Prepare vegetable filling with onion, cabbage, carrot, and spices. Stuff dough balls with filling and steam for 15 minutes.',
                'items' => [
                    ['product' => $attaFlour, 'quantity' => 0.3, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $onion, 'quantity' => 0.15, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $potato, 'quantity' => 0.1, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $garlic, 'quantity' => 0.02, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $ginger, 'quantity' => 0.02, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $greenChilli, 'quantity' => 0.02, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $coriander, 'quantity' => 0.03, 'unit' => $kg, 'wastage' => 15],
                    ['product' => $salt, 'quantity' => 0.01, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $vegetableOil, 'quantity' => 0.02, 'unit' => $litre, 'wastage' => 5],
                ],
            ],

            // Veg Chowmein - Recipe for 1 plate
            [
                'menu_item' => $vegChowmein,
                'preparation_time' => 15,
                'instructions' => 'Boil noodles and set aside. Stir-fry vegetables in oil with garlic, ginger, and spices. Add noodles and toss well. Serve hot.',
                'items' => [
                    ['product' => $onion, 'quantity' => 0.08, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $potato, 'quantity' => 0.05, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $tomato, 'quantity' => 0.05, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $garlic, 'quantity' => 0.015, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $ginger, 'quantity' => 0.015, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $greenChilli, 'quantity' => 0.01, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $vegetableOil, 'quantity' => 0.05, 'unit' => $litre, 'wastage' => 5],
                    ['product' => $salt, 'quantity' => 0.005, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $soySauce ?? null, 'quantity' => 0.02, 'unit' => $litre, 'wastage' => 5],
                ],
            ],

            // Chicken Chowmein - Recipe for 1 plate
            [
                'menu_item' => $chickenChowmein,
                'preparation_time' => 18,
                'instructions' => 'Boil noodles and set aside. Stir-fry chicken strips with vegetables in oil with garlic, ginger, and spices. Add noodles and toss well. Serve hot.',
                'items' => [
                    ['product' => $chicken, 'quantity' => 0.1, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $onion, 'quantity' => 0.08, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $potato, 'quantity' => 0.05, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $tomato, 'quantity' => 0.05, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $garlic, 'quantity' => 0.015, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $ginger, 'quantity' => 0.015, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $greenChilli, 'quantity' => 0.01, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $vegetableOil, 'quantity' => 0.06, 'unit' => $litre, 'wastage' => 5],
                    ['product' => $salt, 'quantity' => 0.005, 'unit' => $kg, 'wastage' => 5],
                ],
            ],

            // Aloo Samosa - Recipe for 10 pieces
            [
                'menu_item' => $alooSamosa,
                'preparation_time' => 10,
                'instructions' => 'Make dough with maida flour and oil. Prepare potato filling with spices. Fill dough cones with potato mixture and deep fry until golden.',
                'items' => [
                    ['product' => $maidaFlour, 'quantity' => 0.25, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $potato, 'quantity' => 0.4, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $onion, 'quantity' => 0.05, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $greenChilli, 'quantity' => 0.01, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $cumin, 'quantity' => 0.005, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $salt, 'quantity' => 0.01, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $vegetableOil, 'quantity' => 0.1, 'unit' => $litre, 'wastage' => 10],
                ],
            ],

            // Vegetable Pakauda - Recipe for 10 pieces
            [
                'menu_item' => $vegPakauda,
                'preparation_time' => 12,
                'instructions' => 'Prepare batter with maida flour and spices. Dip mixed vegetables in batter and deep fry until crispy.',
                'items' => [
                    ['product' => $maidaFlour, 'quantity' => 0.15, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $onion, 'quantity' => 0.1, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $potato, 'quantity' => 0.1, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $greenChilli, 'quantity' => 0.02, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $salt, 'quantity' => 0.005, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $redChilli, 'quantity' => 0.005, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $vegetableOil, 'quantity' => 0.15, 'unit' => $litre, 'wastage' => 15],
                ],
            ],

            // Nepali Thali (Veg) - Recipe for 1 thali
            [
                'menu_item' => $nepaliThaliVeg,
                'preparation_time' => 25,
                'instructions' => 'Prepare dal, rice, vegetable curry, and achar. Serve with salad and papad.',
                'items' => [
                    ['product' => $attaFlour, 'quantity' => 0.2, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $onion, 'quantity' => 0.1, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $potato, 'quantity' => 0.15, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $tomato, 'quantity' => 0.1, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $garlic, 'quantity' => 0.02, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $ginger, 'quantity' => 0.02, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $greenChilli, 'quantity' => 0.02, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $turmeric, 'quantity' => 0.005, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $cumin, 'quantity' => 0.005, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $garamMasala, 'quantity' => 0.005, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $salt, 'quantity' => 0.01, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $vegetableOil, 'quantity' => 0.08, 'unit' => $litre, 'wastage' => 5],
                    ['product' => $paneer, 'quantity' => 0.1, 'unit' => $kg, 'wastage' => 5],
                ],
            ],

            // Nepali Thali (Chicken) - Recipe for 1 thali
            [
                'menu_item' => $nepaliThaliChicken,
                'preparation_time' => 30,
                'instructions' => 'Prepare chicken curry, dal, rice, vegetable curry, and achar. Serve with salad and papad.',
                'items' => [
                    ['product' => $attaFlour, 'quantity' => 0.2, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $chicken, 'quantity' => 0.2, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $onion, 'quantity' => 0.12, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $potato, 'quantity' => 0.15, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $tomato, 'quantity' => 0.12, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $garlic, 'quantity' => 0.03, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $ginger, 'quantity' => 0.03, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $greenChilli, 'quantity' => 0.03, 'unit' => $kg, 'wastage' => 10],
                    ['product' => $turmeric, 'quantity' => 0.008, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $cumin, 'quantity' => 0.008, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $garamMasala, 'quantity' => 0.008, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $salt, 'quantity' => 0.015, 'unit' => $kg, 'wastage' => 5],
                    ['product' => $vegetableOil, 'quantity' => 0.1, 'unit' => $litre, 'wastage' => 5],
                ],
            ],
        ];

        foreach ($recipes as $recipeData) {
            if (!$recipeData['menu_item']) continue;

            $recipe = Recipe::updateOrCreate(
                ['menu_item_id' => $recipeData['menu_item']->id],
                [
                    'menu_item_id' => $recipeData['menu_item']->id,
                    'tenant_id' => 1,
                    'preparation_time' => $recipeData['preparation_time'],
                    'instructions' => $recipeData['instructions'],
                    'selling_price' => $recipeData['menu_item']->price,
                    'status' => 'active',
                ]
            );

            // Delete existing items
            $recipe->items()->delete();

            // Create new items
            foreach ($recipeData['items'] as $itemData) {
                if (!$itemData['product']) continue;

                RecipeItem::create([
                    'recipe_id' => $recipe->id,
                    'product_id' => $itemData['product']->id,
                    'quantity' => $itemData['quantity'],
                    'unit_id' => $itemData['unit']->id,
                    'wastage_percent' => $itemData['wastage'],
                ]);
            }

            // Calculate costs
            foreach ($recipe->items as $item) {
                $item->updateCostFromProduct();
            }

            $recipe->calculateRecipeCost();
            $recipe->calculateFoodCostPercent();
        }
    }
}
