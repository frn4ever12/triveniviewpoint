<?php

namespace Database\Seeders;

use App\Enums\CommonStatusEnum;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Appetizers',
                'slug' => 'appetizers',
                'status' => CommonStatusEnum::ACTIVE,
                'is_featured' => false,
            ],
            [
                'name' => 'Main Courses',
                'slug' => 'mains',
                'status' => CommonStatusEnum::ACTIVE,
                'is_featured' => true,
            ],
            [
                'name' => 'Desserts',
                'slug' => 'desserts',
                'status' => CommonStatusEnum::ACTIVE,
                'is_featured' => false,
            ],
            [
                'name' => 'Beverages',
                'slug' => 'beverages',
                'status' => CommonStatusEnum::ACTIVE,
                'is_featured' => false,
            ],
            [
                'name' => 'Soups',
                'slug' => 'soups',
                'status' => CommonStatusEnum::ACTIVE,
                'is_featured' => false,
            ],
            [
                'name' => 'Salads',
                'slug' => 'salads',
                'status' => CommonStatusEnum::ACTIVE,
                'is_featured' => false,
            ],
            // Nepali Categories
            [
                'name' => 'Momo',
                'slug' => 'momo',
                'status' => CommonStatusEnum::ACTIVE,
                'is_featured' => true,
            ],
            [
                'name' => 'Chowmein/ChauMin',
                'slug' => 'chowmein',
                'status' => CommonStatusEnum::ACTIVE,
                'is_featured' => true,
            ],
            [
                'name' => 'Pakauda/Fryums',
                'slug' => 'pakauda',
                'status' => CommonStatusEnum::ACTIVE,
                'is_featured' => false,
            ],
            [
                'name' => 'Samosa',
                'slug' => 'samosa',
                'status' => CommonStatusEnum::ACTIVE,
                'is_featured' => false,
            ],
            [
                'name' => 'Nepali Thali',
                'slug' => 'nepali-thali',
                'status' => CommonStatusEnum::ACTIVE,
                'is_featured' => true,
            ],
            [
                'name' => 'Nepali Snacks',
                'slug' => 'nepali-snacks',
                'status' => CommonStatusEnum::ACTIVE,
                'is_featured' => false,
            ],
            [
                'name' => 'Tandoori Items',
                'slug' => 'tandoori',
                'status' => CommonStatusEnum::ACTIVE,
                'is_featured' => true,
            ],
        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['tenant_id' => 1])
            );
        }
    }
}
