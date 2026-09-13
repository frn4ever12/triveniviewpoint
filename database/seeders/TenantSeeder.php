<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        Tenant::updateOrCreate(
            ['slug' => 'demo-restaurant'],
            [
                'name' => 'Demo Restaurant',
                'company_name' => 'Demo Restaurant Pvt. Ltd.',
                'email' => 'demo@dmcrestro.com',
                'phone' => '9800000000',
                'address' => 'Nepal',
                'city' => 'Bharatpur',
                'country' => 'Nepal',
                'status' => 'active',
                'trial_ends_at' => now()->addDays(7),
            ]
        );
    }
}