<?php

namespace Database\Seeders;

use App\Enums\CommonStatusEnum;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class NepaliMenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $tenantIds = \App\Models\Tenant::query()->pluck('id')->all();

        if (empty($tenantIds)) {
            $tenantIds = [1];
        }

        $defaultImagePath = public_path('assets/images/defaultfood.png');

        foreach ($tenantIds as $tenantId) {
            $categories = Category::where('tenant_id', $tenantId)->get()->keyBy('slug');

            $menuItems = [
                ['name' => 'Steam Momo (Veg)', 'slug' => 'steam-momo-veg', 'category_slug' => 'momo', 'description' => 'Traditional Nepali steamed vegetable dumplings with spicy tomato chutney', 'ingredients' => 'Flour, cabbage, carrot, onion, garlic, ginger, spices', 'price' => 120, 'cost_price' => 60, 'is_vegetarian' => true, 'is_featured' => true, 'preparation_time' => '15-20 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Steam Momo (Chicken)', 'slug' => 'steam-momo-chicken', 'category_slug' => 'momo', 'description' => 'Juicy chicken dumplings steamed to perfection, served with achar', 'ingredients' => 'Flour, minced chicken, onion, garlic, ginger, spices', 'price' => 150, 'cost_price' => 80, 'is_vegetarian' => false, 'is_featured' => true, 'preparation_time' => '15-20 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Fried Momo (Veg)', 'slug' => 'fried-momo-veg', 'category_slug' => 'momo', 'description' => 'Crispy fried vegetable momos with tangy dipping sauce', 'ingredients' => 'Flour, cabbage, carrot, onion, garlic, ginger, spices', 'price' => 140, 'cost_price' => 75, 'is_vegetarian' => true, 'is_featured' => false, 'preparation_time' => '20-25 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Fried Momo (Chicken)', 'slug' => 'fried-momo-chicken', 'category_slug' => 'momo', 'description' => 'Golden fried chicken momos with spicy chutney', 'ingredients' => 'Flour, minced chicken, onion, garlic, ginger, spices', 'price' => 170, 'cost_price' => 95, 'is_vegetarian' => false, 'is_featured' => false, 'preparation_time' => '20-25 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'C Momo (Chilli Momo)', 'slug' => 'c-momo', 'category_slug' => 'momo', 'description' => 'Spicy stir-fried momos in Indo-Chinese sauce with capsicum and onion', 'ingredients' => 'Momo, capsicum, onion, soy sauce, chilli sauce, garlic', 'price' => 180, 'cost_price' => 100, 'is_vegetarian' => false, 'is_featured' => true, 'preparation_time' => '20-25 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Jhol Momo', 'slug' => 'jhol-momo', 'category_slug' => 'momo', 'description' => 'Momo served in hot and spicy jhol (soup) with sesame and timur', 'ingredients' => 'Momo, sesame, timur, tomato, garlic, ginger, spices', 'price' => 160, 'cost_price' => 90, 'is_vegetarian' => false, 'is_featured' => true, 'preparation_time' => '20-25 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Kothey Momo', 'slug' => 'kothey-momo', 'category_slug' => 'momo', 'description' => 'Half-steamed half-fried momos with crispy bottom and soft top', 'ingredients' => 'Flour, minced chicken/veg, onion, garlic, ginger, spices', 'price' => 160, 'cost_price' => 90, 'is_vegetarian' => false, 'is_featured' => false, 'preparation_time' => '20-25 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Veg Chowmein', 'slug' => 'veg-chowmein', 'category_slug' => 'chowmein', 'description' => 'Stir-fried noodles with mixed vegetables in Nepali style', 'ingredients' => 'Noodles, cabbage, carrot, capsicum, onion, soy sauce', 'price' => 140, 'cost_price' => 70, 'is_vegetarian' => true, 'is_featured' => true, 'preparation_time' => '15-20 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Chicken Chowmein', 'slug' => 'chicken-chowmein', 'category_slug' => 'chowmein', 'description' => 'Spicy chicken chowmein with vegetables and authentic Nepali flavors', 'ingredients' => 'Noodles, chicken, cabbage, carrot, capsicum, soy sauce', 'price' => 180, 'cost_price' => 100, 'is_vegetarian' => false, 'is_featured' => true, 'preparation_time' => '15-20 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Buff Chowmein', 'slug' => 'buff-chowmein', 'category_slug' => 'chowmein', 'description' => 'Traditional Nepali buff chowmein with spicy seasonings', 'ingredients' => 'Noodles, buff meat, cabbage, carrot, onion, soy sauce', 'price' => 170, 'cost_price' => 95, 'is_vegetarian' => false, 'is_featured' => false, 'preparation_time' => '15-20 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Aloo Pakauda', 'slug' => 'aloo-pakauda', 'category_slug' => 'pakauda', 'description' => 'Crispy potato fritters seasoned with spices', 'ingredients' => 'Potato, gram flour, spices, oil', 'price' => 80, 'cost_price' => 40, 'is_vegetarian' => true, 'is_featured' => false, 'preparation_time' => '10-15 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Chicken Pakauda', 'slug' => 'chicken-pakauda', 'category_slug' => 'pakauda', 'description' => 'Spicy chicken fritters with crispy coating', 'ingredients' => 'Chicken, gram flour, spices, oil', 'price' => 150, 'cost_price' => 85, 'is_vegetarian' => false, 'is_featured' => true, 'preparation_time' => '15-20 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Vegetable Pakauda', 'slug' => 'veg-pakauda', 'category_slug' => 'pakauda', 'description' => 'Mixed vegetable fritters with gram flour batter', 'ingredients' => 'Mixed vegetables, gram flour, spices, oil', 'price' => 100, 'cost_price' => 55, 'is_vegetarian' => true, 'is_featured' => false, 'preparation_time' => '10-15 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Paneer Pakauda', 'slug' => 'paneer-pakauda', 'category_slug' => 'pakauda', 'description' => 'Crispy paneer fritters with aromatic spices', 'ingredients' => 'Paneer, gram flour, spices, oil', 'price' => 140, 'cost_price' => 80, 'is_vegetarian' => true, 'is_featured' => false, 'preparation_time' => '15-20 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Aloo Samosa', 'slug' => 'aloo-samosa', 'category_slug' => 'samosa', 'description' => 'Crispy triangular pastry filled with spiced potato', 'ingredients' => 'Flour, potato, peas, spices, oil', 'price' => 30, 'cost_price' => 15, 'is_vegetarian' => true, 'is_featured' => true, 'preparation_time' => '10-15 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Chicken Samosa', 'slug' => 'chicken-samosa', 'category_slug' => 'samosa', 'description' => 'Crispy samosa filled with spiced chicken mince', 'ingredients' => 'Flour, chicken, onion, spices, oil', 'price' => 50, 'cost_price' => 28, 'is_vegetarian' => false, 'is_featured' => false, 'preparation_time' => '15-20 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Nepali Veg Thali', 'slug' => 'nepali-veg-thali', 'category_slug' => 'nepali-thali', 'description' => 'Complete Nepali vegetarian meal with rice, dal, tarkari, achar, and salad', 'ingredients' => 'Rice, dal, seasonal vegetables, pickle, salad', 'price' => 250, 'cost_price' => 140, 'is_vegetarian' => true, 'is_featured' => true, 'preparation_time' => '25-30 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Nepali Non-Veg Thali', 'slug' => 'nepali-nonveg-thali', 'category_slug' => 'nepali-thali', 'description' => 'Complete Nepali meal with chicken curry, rice, dal, tarkari, achar, and salad', 'ingredients' => 'Rice, dal, chicken curry, seasonal vegetables, pickle, salad', 'price' => 350, 'cost_price' => 200, 'is_vegetarian' => false, 'is_featured' => true, 'preparation_time' => '30-35 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Buff Thali', 'slug' => 'buff-thali', 'category_slug' => 'nepali-thali', 'description' => 'Traditional Nepali thali with buff meat curry, rice, dal, and sides', 'ingredients' => 'Rice, dal, buff curry, seasonal vegetables, pickle, salad', 'price' => 320, 'cost_price' => 180, 'is_vegetarian' => false, 'is_featured' => false, 'preparation_time' => '30-35 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Sel Roti', 'slug' => 'sel-roti', 'category_slug' => 'nepali-snacks', 'description' => 'Traditional Nepali sweet ring-shaped rice bread', 'ingredients' => 'Rice flour, sugar, milk, ghee, cardamom', 'price' => 40, 'cost_price' => 20, 'is_vegetarian' => true, 'is_featured' => true, 'preparation_time' => '20-25 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Chiura', 'slug' => 'chiura', 'category_slug' => 'nepali-snacks', 'description' => 'Flattened rice, served with achar or curry', 'ingredients' => 'Flattened rice', 'price' => 50, 'cost_price' => 25, 'is_vegetarian' => true, 'is_featured' => false, 'preparation_time' => '5 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Dhido', 'slug' => 'dhido', 'category_slug' => 'nepali-snacks', 'description' => 'Traditional Nepali food made from buckwheat or millet flour', 'ingredients' => 'Buckwheat flour, millet flour, water', 'price' => 120, 'cost_price' => 60, 'is_vegetarian' => true, 'is_featured' => true, 'preparation_time' => '20-25 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Gundruk', 'slug' => 'gundruk', 'category_slug' => 'nepali-snacks', 'description' => 'Fermented leafy green vegetable, traditional Nepali delicacy', 'ingredients' => 'Fermented leafy greens, spices', 'price' => 100, 'cost_price' => 50, 'is_vegetarian' => true, 'is_featured' => false, 'preparation_time' => '15-20 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Tandoori Chicken', 'slug' => 'tandoori-chicken', 'category_slug' => 'tandoori', 'description' => 'Chicken marinated in yogurt and spices, cooked in tandoor', 'ingredients' => 'Chicken, yogurt, tandoori spices, lemon, garlic', 'price' => 350, 'cost_price' => 200, 'is_vegetarian' => false, 'is_featured' => true, 'preparation_time' => '30-35 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Chicken Tikka', 'slug' => 'chicken-tikka', 'category_slug' => 'tandoori', 'description' => 'Boneless chicken pieces marinated and grilled in tandoor', 'ingredients' => 'Chicken, yogurt, tikka spices, lemon, garlic', 'price' => 320, 'cost_price' => 180, 'is_vegetarian' => false, 'is_featured' => true, 'preparation_time' => '25-30 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Tandoori Paneer', 'slug' => 'tandoori-paneer', 'category_slug' => 'tandoori', 'description' => 'Paneer cubes marinated in spices and grilled in tandoor', 'ingredients' => 'Paneer, yogurt, tandoori spices, lemon', 'price' => 280, 'cost_price' => 160, 'is_vegetarian' => true, 'is_featured' => false, 'preparation_time' => '25-30 mins', 'status' => CommonStatusEnum::ACTIVE],
                ['name' => 'Seekh Kebab', 'slug' => 'seekh-kebab', 'category_slug' => 'tandoori', 'description' => 'Spiced minced meat skewers grilled in tandoor', 'ingredients' => 'Minced meat, onion, spices, herbs', 'price' => 300, 'cost_price' => 170, 'is_vegetarian' => false, 'is_featured' => false, 'preparation_time' => '25-30 mins', 'status' => CommonStatusEnum::ACTIVE],
            ];

            foreach ($menuItems as $itemData) {
                $category = $categories->get($itemData['category_slug']);

                if (! $category) {
                    $this->command->warn("Category not found for tenant {$tenantId}: {$itemData['category_slug']}");
                    continue;
                }

                $menuItem = MenuItem::updateOrCreate(
                    ['slug' => $itemData['slug'], 'tenant_id' => $tenantId],
                    [
                        'tenant_id' => $tenantId,
                        'name' => $itemData['name'],
                        'slug' => $itemData['slug'],
                        'category_id' => $category->id,
                        'description' => $itemData['description'],
                        'ingredients' => $itemData['ingredients'],
                        'price' => $itemData['price'],
                        'cost_price' => $itemData['cost_price'],
                        'final_price' => $itemData['price'],
                        'is_vegetarian' => $itemData['is_vegetarian'],
                        'is_featured' => $itemData['is_featured'],
                        'preparation_time' => $itemData['preparation_time'],
                        'status' => $itemData['status'],
                    ]
                );

                if ($menuItem && ! $menuItem->hasMedia('image') && file_exists($defaultImagePath)) {
                    $menuItem->addMedia($defaultImagePath)->preservingOriginal()->toMediaCollection('image');
                }
            }
        }

        $this->command->info('Nepali menu items seeded successfully for all tenants!');
    }
}
