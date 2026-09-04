<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'utility',
            ],
            [
                'name' => 'salary',
            ],
            [
                'name' => 'rent',
            ],
            [
                'name' => 'electricity',
            ],
            [
                'name' => 'other',
            ]
        ];
        DB::table('labels')->insert($data);
    }
}
