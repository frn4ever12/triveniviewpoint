<?php

namespace Database\Seeders;

use App\Enums\CommonStatusEnum;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        $appetizers = Category::where('slug', 'appetizers')->first();
        $mains = Category::where('slug', 'mains')->first();
        $desserts = Category::where('slug', 'desserts')->first();
        $beverages = Category::where('slug', 'beverages')->first();
        $soups = Category::where('slug', 'soups')->first();
        $salads = Category::where('slug', 'salads')->first();

        $items = [
            // Appetizers
            [
                'name' => 'Truffle Risotto',
                'slug' => 'truffle-risotto',
                'category_id' => $appetizers?->id,
                'price' => 18.00,
                'description' => 'Creamy Arborio rice with black truffle, parmesan cheese, and fresh herbs.',
                'preparation_time' => 25,
                'is_vegetarian' => true,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Burrata Caprese',
                'slug' => 'burrata-caprese',
                'category_id' => $appetizers?->id,
                'price' => 16.00,
                'description' => 'Fresh burrata cheese with heirloom tomatoes, basil, and aged balsamic reduction.',
                'preparation_time' => 10,
                'is_vegetarian' => true,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Bruschetta',
                'slug' => 'bruschetta',
                'category_id' => $appetizers?->id,
                'price' => 12.00,
                'description' => 'Grilled bread topped with fresh tomatoes, garlic, basil, and olive oil.',
                'preparation_time' => 8,
                'is_vegetarian' => true,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],

            // Main Courses
            [
                'name' => 'Osso Buco Milanese',
                'slug' => 'osso-buco-milanese',
                'category_id' => $mains?->id,
                'price' => 42.00,
                'description' => 'Braised veal shanks with saffron risotto, gremolata, and seasonal vegetables.',
                'preparation_time' => 35,
                'is_vegetarian' => false,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Pasta alla Norma',
                'slug' => 'pasta-alla-norma',
                'category_id' => $mains?->id,
                'price' => 22.00,
                'description' => 'Sicilian pasta with eggplant, tomatoes, basil, and ricotta salata cheese.',
                'preparation_time' => 20,
                'is_vegetarian' => true,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Grilled Salmon',
                'slug' => 'grilled-salmon',
                'category_id' => $mains?->id,
                'price' => 35.00,
                'description' => 'Fresh Atlantic salmon with lemon butter sauce and seasonal vegetables.',
                'preparation_time' => 25,
                'is_vegetarian' => false,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Chicken Parmesan',
                'slug' => 'chicken-parmesan',
                'category_id' => $mains?->id,
                'price' => 28.00,
                'description' => 'Breaded chicken breast with marinara sauce and melted mozzarella.',
                'preparation_time' => 22,
                'is_vegetarian' => false,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],

            // Desserts
            [
                'name' => 'Tiramisu Classico',
                'slug' => 'tiramisu-classico',
                'category_id' => $desserts?->id,
                'price' => 12.00,
                'description' => 'Traditional Italian dessert with espresso-soaked ladyfingers, mascarpone, and cocoa.',
                'preparation_time' => 5,
                'is_vegetarian' => true,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Gelato Trio',
                'slug' => 'gelato-trio',
                'category_id' => $desserts?->id,
                'price' => 9.00,
                'description' => 'Three scoops of artisanal gelato: pistachio, stracciatella, and amaretto.',
                'preparation_time' => 3,
                'is_vegetarian' => true,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Panna Cotta',
                'slug' => 'panna-cotta',
                'category_id' => $desserts?->id,
                'price' => 10.00,
                'description' => 'Silky vanilla cream dessert with berry compote.',
                'preparation_time' => 4,
                'is_vegetarian' => true,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],

            // Beverages
            [
                'name' => 'Negroni Sbagliato',
                'slug' => 'negroni-sbagliato',
                'category_id' => $beverages?->id,
                'price' => 16.00,
                'description' => 'Classic Italian aperitif with Campari, sweet vermouth, and prosecco.',
                'preparation_time' => 3,
                'is_vegetarian' => true,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Espresso Romano',
                'slug' => 'espresso-romano',
                'category_id' => $beverages?->id,
                'price' => 4.00,
                'description' => 'Traditional Italian espresso served with a twist of lemon peel.',
                'preparation_time' => 2,
                'is_vegetarian' => true,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Lemon Spritz',
                'slug' => 'lemon-spritz',
                'category_id' => $beverages?->id,
                'price' => 12.00,
                'description' => 'Refreshing spritz with prosecco, Aperol, and fresh lemon.',
                'preparation_time' => 3,
                'is_vegetarian' => true,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],

            // Soups
            [
                'name' => 'Tomato Basil Soup',
                'slug' => 'tomato-basil-soup',
                'category_id' => $soups?->id,
                'price' => 8.00,
                'description' => 'Creamy tomato soup with fresh basil and garlic croutons.',
                'preparation_time' => 15,
                'is_vegetarian' => true,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Minestrone',
                'slug' => 'minestrone',
                'category_id' => $soups?->id,
                'price' => 10.00,
                'description' => 'Hearty Italian vegetable soup with pasta and beans.',
                'preparation_time' => 20,
                'is_vegetarian' => true,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],

            // Salads
            [
                'name' => 'Caesar Salad',
                'slug' => 'caesar-salad',
                'category_id' => $salads?->id,
                'price' => 14.00,
                'description' => 'Crisp romaine lettuce with parmesan, croutons, and Caesar dressing.',
                'preparation_time' => 8,
                'is_vegetarian' => true,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Greek Salad',
                'slug' => 'greek-salad',
                'category_id' => $salads?->id,
                'price' => 13.00,
                'description' => 'Fresh vegetables with feta cheese, olives, and olive oil dressing.',
                'preparation_time' => 10,
                'is_vegetarian' => true,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],
        ];

        foreach ($items as $data) {
            MenuItem::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
