<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datas=[
            ['name'=>'Table-1', 'status'=>'available'],
            ['name'=>'Table-2', 'status'=>'available'],
            ['name'=>'Table-3', 'status'=>'available'],
            ['name'=>'Table-4', 'status'=>'available'],
            ['name'=>'Table-5', 'status'=>'available'],
            ['name'=>'Table-6', 'status'=>'available'],
            ['name'=>'Table-7', 'status'=>'available'],
            ['name'=>'Table-8', 'status'=>'available'],
        ];
        foreach ($datas as $data) {
            \App\Models\Table::updateOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['tenant_id' => 1])
            );
        }
    }
}
