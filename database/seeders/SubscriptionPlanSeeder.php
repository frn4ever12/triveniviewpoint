<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Perfect for small restaurants and cafes',
                'monthly_price' => 1500,
                'yearly_price' => 15000,
                'trial_days' => 14,
                'max_users' => 2,
                'max_menu_items' => 50,
                'max_orders_per_month' => 500,
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Professional',
                'slug' => 'professional',
                'description' => 'Ideal for growing restaurants',
                'monthly_price' => 3500,
                'yearly_price' => 35000,
                'trial_days' => 14,
                'max_users' => 5,
                'max_menu_items' => 200,
                'max_orders_per_month' => 2000,
                'is_active' => true,
                'is_popular' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'For large restaurant chains',
                'monthly_price' => 7500,
                'yearly_price' => 75000,
                'trial_days' => 30,
                'max_users' => 15,
                'max_menu_items' => 1000,
                'max_orders_per_month' => null,
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug']],
                $plan
            );
        }
    }
}
