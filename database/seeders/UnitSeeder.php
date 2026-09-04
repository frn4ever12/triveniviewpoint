<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'kg',
            ],
            [
                'name' => 'ton',
            ],
            [
                'name' => 'gram',
            ],
            [
                'name' => 'litre',
            ],
            [
                'name' => 'gallon',
            ]
        ];
        DB::table('units')->insert($data);
    }
}
