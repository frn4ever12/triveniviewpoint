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
            ['name'=>'Table-1', 'capacity'=>4, 'table_type'=>'indoor', 'location'=>'ground floor', 'status'=>'available'],
            ['name'=>'Table-2', 'capacity'=>4, 'table_type'=>'indoor', 'location'=>'ground floor', 'status'=>'available'],
            ['name'=>'Table-3', 'capacity'=>6, 'table_type'=>'indoor', 'location'=>'ground floor', 'status'=>'available'],
            ['name'=>'Table-4', 'capacity'=>6, 'table_type'=>'indoor', 'location'=>'ground floor', 'status'=>'available'],
            ['name'=>'Table-5', 'capacity'=>4, 'table_type'=>'indoor', 'location'=>'first floor', 'status'=>'available'],
            ['name'=>'Table-6', 'capacity'=>4, 'table_type'=>'indoor', 'location'=>'first floor', 'status'=>'available'],
            ['name'=>'Table-7', 'capacity'=>8, 'table_type'=>'family', 'location'=>'first floor', 'status'=>'available'],
            ['name'=>'Table-8', 'capacity'=>8, 'table_type'=>'family', 'location'=>'first floor', 'status'=>'available'],
            ['name'=>'Table-9', 'capacity'=>2, 'table_type'=>'couple', 'location'=>'terrace', 'status'=>'available'],
            ['name'=>'Table-10', 'capacity'=>2, 'table_type'=>'couple', 'location'=>'terrace', 'status'=>'available'],
            ['name'=>'Table-11', 'capacity'=>10, 'table_type'=>'party', 'location'=>'private room', 'status'=>'available'],
            ['name'=>'Table-12', 'capacity'=>12, 'table_type'=>'party', 'location'=>'private room', 'status'=>'available'],
        ];
        foreach ($datas as $data) {
            \App\Models\Table::updateOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['tenant_id' => 1])
            );
        }
    }
}
