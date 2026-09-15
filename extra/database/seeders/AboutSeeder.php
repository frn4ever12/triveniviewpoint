<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AboutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'title' => 'Empowering Restaurants to Thrive',
                'description' => 'At RestaurantPro, we are dedicated to simplifying restaurant management with our innovative platform. Trusted by thousands of restaurants worldwide, we help streamline operations, enhance customer experiences, and drive profitability—all in one easy-to-use solution.',
            ],
           
        ];
        DB::table('abouts')->insert($data);
    }
}
