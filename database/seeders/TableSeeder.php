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
        // Get all tenants to seed tables for each tenant
        $tenants = \App\Models\Tenant::all();
        
        if ($tenants->isEmpty()) {
            $this->command->warn('No tenants found. Please run TenantSeeder first.');
            return;
        }

        $tables = [
            ['name' => 'Table 1', 'capacity' => 4, 'table_type' => 'indoor', 'location' => 'ground floor', 'status' => 'available'],
            ['name' => 'Cabin', 'capacity' => 4, 'table_type' => 'cabin', 'location' => 'ground floor', 'status' => 'available'],
            ['name' => 'Table 3', 'capacity' => 6, 'table_type' => 'indoor', 'location' => 'ground floor', 'status' => 'available'],
            ['name' => 'Table 4', 'capacity' => 6, 'table_type' => 'indoor', 'location' => 'ground floor', 'status' => 'available'],
            ['name' => 'Table 5', 'capacity' => 4, 'table_type' => 'indoor', 'location' => 'first floor', 'status' => 'available'],
            ['name' => 'Table 6', 'capacity' => 4, 'table_type' => 'indoor', 'location' => 'first floor', 'status' => 'available'],
            ['name' => 'Table 7', 'capacity' => 8, 'table_type' => 'family', 'location' => 'first floor', 'status' => 'available'],
            ['name' => 'Table 8', 'capacity' => 8, 'table_type' => 'family', 'location' => 'first floor', 'status' => 'available'],
            ['name' => 'Table 9', 'capacity' => 2, 'table_type' => 'couple', 'location' => 'terrace', 'status' => 'available'],
            ['name' => 'Table 10', 'capacity' => 2, 'table_type' => 'couple', 'location' => 'terrace', 'status' => 'available'],
            ['name' => 'Table 11', 'capacity' => 10, 'table_type' => 'party', 'location' => 'private room', 'status' => 'available'],
            ['name' => 'Table 12', 'capacity' => 12, 'table_type' => 'party', 'location' => 'private room', 'status' => 'available'],
        ];

        foreach ($tenants as $tenant) {
            $this->command->info("Seeding tables for tenant: {$tenant->name}");
            
            foreach ($tables as $tableData) {
                \App\Models\Table::updateOrCreate(
                    [
                        'name' => $tableData['name'],
                        'tenant_id' => $tenant->id,
                    ],
                    array_merge($tableData, ['tenant_id' => $tenant->id])
                );
            }
        }

        $this->command->info('Tables seeded successfully for all tenants!');
    }
}
