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
        $momo = Category::where('slug', 'momo')->first();
        $chowmein = Category::where('slug', 'chowmein')->first();
        $pakauda = Category::where('slug', 'pakauda')->first();
        $samosa = Category::where('slug', 'samosa')->first();
        $nepaliThali = Category::where('slug', 'nepali-thali')->first();
        $nepaliSnacks = Category::where('slug', 'nepali-snacks')->first();
        $tandoori = Category::where('slug', 'tandoori')->first();

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

            // Momo
            [
                'name' => 'Steam Momo (Chicken)',
                'slug' => 'steam-momo-chicken',
                'category_id' => $momo?->id,
                'price' => 180.00,
                'description' => 'Steamed chicken dumplings served with tomato achar and chutney.',
                'preparation_time' => 15,
                'is_vegetarian' => false,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Steam Momo (Buff)',
                'slug' => 'steam-momo-buff',
                'category_id' => $momo?->id,
                'price' => 160.00,
                'description' => 'Steamed buff dumplings served with tomato achar and chutney.',
                'preparation_time' => 15,
                'is_vegetarian' => false,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Steam Momo (Veg)',
                'slug' => 'steam-momo-veg',
                'category_id' => $momo?->id,
                'price' => 150.00,
                'description' => 'Steamed vegetable dumplings served with tomato achar and chutney.',
                'preparation_time' => 15,
                'is_vegetarian' => true,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Fried Momo (Chicken)',
                'slug' => 'fried-momo-chicken',
                'category_id' => $momo?->id,
                'price' => 200.00,
                'description' => 'Deep fried chicken dumplings served with tomato achar and chutney.',
                'preparation_time' => 20,
                'is_vegetarian' => false,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'C Momo (Chilli Momo)',
                'slug' => 'c-momo',
                'category_id' => $momo?->id,
                'price' => 220.00,
                'description' => 'Spicy chilli momo prepared with bell peppers, onions, and special sauce.',
                'preparation_time' => 25,
                'is_vegetarian' => false,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Kothey Momo',
                'slug' => 'kothey-momo',
                'category_id' => $momo?->id,
                'price' => 190.00,
                'description' => 'Half steamed and half fried momo served with chutney.',
                'preparation_time' => 18,
                'is_vegetarian' => false,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],

            // Chowmein
            [
                'name' => 'Veg Chowmein',
                'slug' => 'veg-chowmein',
                'category_id' => $chowmein?->id,
                'price' => 180.00,
                'description' => 'Stir-fried noodles with mixed vegetables and Nepali spices.',
                'preparation_time' => 15,
                'is_vegetarian' => true,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Chicken Chowmein',
                'slug' => 'chicken-chowmein',
                'category_id' => $chowmein?->id,
                'price' => 220.00,
                'description' => 'Stir-fried noodles with chicken strips and vegetables.',
                'preparation_time' => 18,
                'is_vegetarian' => false,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Buff Chowmein',
                'slug' => 'buff-chowmein',
                'category_id' => $chowmein?->id,
                'price' => 200.00,
                'description' => 'Stir-fried noodles with buff strips and vegetables.',
                'preparation_time' => 18,
                'is_vegetarian' => false,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],

            // Pakauda
            [
                'name' => 'Vegetable Pakauda',
                'slug' => 'veg-pakauda',
                'category_id' => $pakauda?->id,
                'price' => 120.00,
                'description' => 'Mixed vegetable fritters deep fried to golden perfection.',
                'preparation_time' => 12,
                'is_vegetarian' => true,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Chicken Pakauda',
                'slug' => 'chicken-pakauda',
                'category_id' => $pakauda?->id,
                'price' => 180.00,
                'description' => 'Crispy chicken fritters with spices.',
                'preparation_time' => 15,
                'is_vegetarian' => false,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Paneer Pakauda',
                'slug' => 'paneer-pakauda',
                'category_id' => $pakauda?->id,
                'price' => 160.00,
                'description' => 'Crispy paneer fritters with gram flour coating.',
                'preparation_time' => 12,
                'is_vegetarian' => true,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],

            // Samosa
            [
                'name' => 'Aloo Samosa',
                'slug' => 'aloo-samosa',
                'category_id' => $samosa?->id,
                'price' => 30.00,
                'description' => 'Crispy triangular pastry filled with spiced potato mixture.',
                'preparation_time' => 10,
                'is_vegetarian' => true,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Chicken Samosa',
                'slug' => 'chicken-samosa',
                'category_id' => $samosa?->id,
                'price' => 50.00,
                'description' => 'Crispy triangular pastry filled with spiced chicken mixture.',
                'preparation_time' => 12,
                'is_vegetarian' => false,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],

            // Nepali Thali
            [
                'name' => 'Nepali Thali (Chicken)',
                'slug' => 'nepali-thali-chicken',
                'category_id' => $nepaliThali?->id,
                'price' => 450.00,
                'description' => 'Complete Nepali meal with chicken curry, rice, dal, bhat tarkari, achar, and salad.',
                'preparation_time' => 30,
                'is_vegetarian' => false,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Nepali Thali (Veg)',
                'slug' => 'nepali-thali-veg',
                'category_id' => $nepaliThali?->id,
                'price' => 350.00,
                'description' => 'Complete vegetarian Nepali meal with paneer/mushroom curry, rice, dal, bhat tarkari, achar, and salad.',
                'preparation_time' => 25,
                'is_vegetarian' => true,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Nepali Thali (Buff)',
                'slug' => 'nepali-thali-buff',
                'category_id' => $nepaliThali?->id,
                'price' => 400.00,
                'description' => 'Complete Nepali meal with buff curry, rice, dal, bhat tarkari, achar, and salad.',
                'preparation_time' => 30,
                'is_vegetarian' => false,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],

            // Nepali Snacks
            [
                'name' => 'Sekuwa',
                'slug' => 'sekuwa',
                'category_id' => $nepaliSnacks?->id,
                'price' => 350.00,
                'description' => 'Grilled marinated meat (chicken/buff) with Nepali spices.',
                'preparation_time' => 25,
                'is_vegetarian' => false,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Choila',
                'slug' => 'choila',
                'category_id' => $nepaliSnacks?->id,
                'price' => 300.00,
                'description' => 'Spiced grilled buff meat with mustard oil and spices.',
                'preparation_time' => 20,
                'is_vegetarian' => false,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Bara',
                'slug' => 'bara',
                'category_id' => $nepaliSnacks?->id,
                'price' => 80.00,
                'description' => 'Traditional lentil pancake served with achar.',
                'preparation_time' => 15,
                'is_vegetarian' => true,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Chatamari',
                'slug' => 'chatamari',
                'category_id' => $nepaliSnacks?->id,
                'price' => 120.00,
                'description' => 'Newari rice crepe topped with meat, egg, and vegetables.',
                'preparation_time' => 18,
                'is_vegetarian' => false,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],

            // Tandoori Items
            [
                'name' => 'Tandoori Chicken',
                'slug' => 'tandoori-chicken',
                'category_id' => $tandoori?->id,
                'price' => 450.00,
                'description' => 'Marinated chicken grilled in tandoor with aromatic spices.',
                'preparation_time' => 25,
                'is_vegetarian' => false,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Tandoori Chicken Wings',
                'slug' => 'tandoori-chicken-wings',
                'category_id' => $tandoori?->id,
                'price' => 280.00,
                'description' => 'Spicy tandoori chicken wings with mint chutney.',
                'preparation_time' => 20,
                'is_vegetarian' => false,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'Tandoori Paneer',
                'slug' => 'tandoori-paneer',
                'category_id' => $tandoori?->id,
                'price' => 320.00,
                'description' => 'Marinated paneer grilled in tandoor with spices.',
                'preparation_time' => 18,
                'is_vegetarian' => true,
                'is_featured' => false,
                'status' => CommonStatusEnum::ACTIVE,
            ],
            [
                'name' => 'ChickenTikka',
                'slug' => 'chicken-tikka',
                'category_id' => $tandoori?->id,
                'price' => 380.00,
                'description' => 'Boneless chicken pieces marinated and grilled in tandoor.',
                'preparation_time' => 22,
                'is_vegetarian' => false,
                'is_featured' => true,
                'status' => CommonStatusEnum::ACTIVE,
            ],
        ];

        foreach ($items as $data) {
            $menuItem = MenuItem::updateOrCreate(['slug' => $data['slug']], $data);
            
            // Add image if menu item exists and doesn't have one
            if ($menuItem && !$menuItem->getFirstMedia('image')) {
                $imageUrl = $this->getImageUrlForItem($data['slug']);
                if ($imageUrl) {
                    try {
                        $menuItem->addMediaFromUrl($imageUrl)
                            ->toMediaCollection('image');
                    } catch (\Exception $e) {
                        // Skip if image download fails
                    }
                }
            }
        }
    }

    private function getImageUrlForItem($slug)
    {
        // Using placeholder images from Unsplash or similar services
        $images = [
            // Momo
            'steam-momo-chicken' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=400&h=400&fit=crop',
            'steam-momo-buff' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=400&h=400&fit=crop',
            'steam-momo-veg' => 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=400&h=400&fit=crop',
            'fried-momo-chicken' => 'https://images.unsplash.com/photo-1563245372-f21724e3856d?w=400&h=400&fit=crop',
            'c-momo' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=400&h=400&fit=crop',
            'kothey-momo' => 'https://images.unsplash.com/photo-1606491956689-2ea866880c84?w=400&h=400&fit=crop',
            
            // Chowmein
            'veg-chowmein' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?w=400&h=400&fit=crop',
            'chicken-chowmein' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=400&h=400&fit=crop',
            'buff-chowmein' => 'https://images.unsplash.com/photo-1569718212165-3a8278d5f624?w=400&h=400&fit=crop',
            
            // Pakauda
            'veg-pakauda' => 'https://images.unsplash.com/photo-1626074353765-517a681e40be?w=400&h=400&fit=crop',
            'chicken-pakauda' => 'https://images.unsplash.com/photo-1562967916-eb82221dfb92?w=400&h=400&fit=crop',
            'paneer-pakauda' => 'https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=400&h=400&fit=crop',
            
            // Samosa
            'aloo-samosa' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=400&h=400&fit=crop',
            'chicken-samosa' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=400&h=400&fit=crop',
            
            // Nepali Thali
            'nepali-thali-chicken' => 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=400&h=400&fit=crop',
            'nepali-thali-veg' => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=400&h=400&fit=crop',
            'nepali-thali-buff' => 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=400&h=400&fit=crop',
            
            // Nepali Snacks
            'sekuwa' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=400&h=400&fit=crop',
            'choila' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=400&h=400&fit=crop',
            'bara' => 'https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=400&h=400&fit=crop',
            'chatamari' => 'https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=400&h=400&fit=crop',
            
            // Tandoori
            'tandoori-chicken' => 'https://images.unsplash.com/photo-1599487488170-d11ec9e172e0?w=400&h=400&fit=crop',
            'tandoori-chicken-wings' => 'https://images.unsplash.com/photo-1527477396000-e27163b481c2?w=400&h=400&fit=crop',
            'tandoori-paneer' => 'https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=400&h=400&fit=crop',
            'chicken-tikka' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=400&h=400&fit=crop',
            
            // Default for other items
            'default' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=400&h=400&fit=crop',
        ];

        return $images[$slug] ?? $images['default'];
    }
}
