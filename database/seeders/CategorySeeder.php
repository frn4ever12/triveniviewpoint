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
        ];

        foreach ($categories as $data) {
            Category::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
