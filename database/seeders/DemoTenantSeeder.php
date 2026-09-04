<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DemoTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the professional plan
        $plan = SubscriptionPlan::where('slug', 'professional')->first();
        if (!$plan) {
            $this->command->warn('Professional plan not found. Please run SubscriptionPlanSeeder first.');
            return;
        }

        // Create demo tenants
        $tenants = [
            [
                'name' => 'The Royal Kitchen',
                'slug' => 'royal-kitchen',
                'company_name' => 'Royal Kitchen Pvt Ltd',
                'email' => 'info@royalkitchen.com',
                'phone' => '+977-9841234567',
                'address' => 'Thamel, Kathmandu',
                'city' => 'Kathmandu',
                'country' => 'Nepal',
                'pan_no' => '123456789',
                'status' => 'active',
            ],
            [
                'name' => 'Mountain View Cafe',
                'slug' => 'mountain-view-cafe',
                'company_name' => 'Mountain View Cafe',
                'email' => 'hello@mountainview.com',
                'phone' => '+977-9851234567',
                'address' => 'Lakeside, Pokhara',
                'city' => 'Pokhara',
                'country' => 'Nepal',
                'pan_no' => '987654321',
                'status' => 'active',
            ],
            [
                'name' => 'Spice Garden Restaurant',
                'slug' => 'spice-garden',
                'company_name' => 'Spice Garden',
                'email' => 'contact@spicegarden.com',
                'phone' => '+977-9861234567',
                'address' => 'New Baneshwor, Kathmandu',
                'city' => 'Kathmandu',
                'country' => 'Nepal',
                'pan_no' => '456123789',
                'status' => 'active',
            ],
        ];

        foreach ($tenants as $tenantData) {
            $tenant = Tenant::updateOrCreate(
                ['slug' => $tenantData['slug']],
                $tenantData
            );

            // Create subscription for tenant
            $subscription = Subscription::updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'status' => 'active',
                ],
                [
                    'tenant_id' => $tenant->id,
                    'plan_id' => $plan->id,
                    'billing_cycle' => 'monthly',
                    'amount' => $plan->monthly_price,
                    'starts_at' => now(),
                    'ends_at' => now()->addMonth(),
                    'next_billing_at' => now()->addMonth(),
                    'status' => 'active',
                ]
            );

            // Create admin user for tenant
            $adminEmail = str_replace(' ', '', strtolower($tenant->name)) . '@admin.com';
            User::updateOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => $tenant->name . ' Admin',
                    'email' => $adminEmail,
                    'password' => Hash::make('password123'),
                    'tenant_id' => $tenant->id,
                    'status' => 'active',
                ]
            );

            $this->command->info("Created tenant: {$tenant->name}");
        }

        $this->command->info('Demo tenants created successfully!');
    }
}
