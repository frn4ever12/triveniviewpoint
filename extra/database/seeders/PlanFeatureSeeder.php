<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubscriptionPlan;
use App\Models\PlanFeature;

class PlanFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $starterPlan = SubscriptionPlan::where('slug', 'starter')->first();
        $professionalPlan = SubscriptionPlan::where('slug', 'professional')->first();
        $enterprisePlan = SubscriptionPlan::where('slug', 'enterprise')->first();

        $features = [
            // Starter Plan Features
            ['plan_id' => $starterPlan->id, 'code' => 'pos_basic', 'name' => 'Basic POS', 'description' => 'Point of sale functionality', 'is_enabled' => true, 'value' => null, 'sort_order' => 1],
            ['plan_id' => $starterPlan->id, 'code' => 'menu_management', 'name' => 'Menu Management', 'description' => 'Manage menu items and categories', 'is_enabled' => true, 'value' => null, 'sort_order' => 2],
            ['plan_id' => $starterPlan->id, 'code' => 'order_management', 'name' => 'Order Management', 'description' => 'Create and manage orders', 'is_enabled' => true, 'value' => null, 'sort_order' => 3],
            ['plan_id' => $starterPlan->id, 'code' => 'basic_reports', 'name' => 'Basic Reports', 'description' => 'Daily sales and order reports', 'is_enabled' => true, 'value' => null, 'sort_order' => 4],
            ['plan_id' => $starterPlan->id, 'code' => 'kitchen_display', 'name' => 'Kitchen Display', 'description' => 'Kitchen ticket display system', 'is_enabled' => true, 'value' => null, 'sort_order' => 5],
            ['plan_id' => $starterPlan->id, 'code' => 'inventory_basic', 'name' => 'Basic Inventory', 'description' => 'Track stock levels', 'is_enabled' => true, 'value' => null, 'sort_order' => 6],
            ['plan_id' => $starterPlan->id, 'code' => 'digital_menu', 'name' => 'Digital Menu', 'description' => 'QR code menu for customers', 'is_enabled' => false, 'value' => null, 'sort_order' => 7],
            ['plan_id' => $starterPlan->id, 'code' => 'multi_location', 'name' => 'Multi Location', 'description' => 'Manage multiple locations', 'is_enabled' => false, 'value' => null, 'sort_order' => 8],
            ['plan_id' => $starterPlan->id, 'code' => 'api_access', 'name' => 'API Access', 'description' => 'REST API integration', 'is_enabled' => false, 'value' => null, 'sort_order' => 9],
            ['plan_id' => $starterPlan->id, 'code' => 'support_email', 'name' => 'Email Support', 'description' => 'Email support within 24 hours', 'is_enabled' => true, 'value' => null, 'sort_order' => 10],

            // Professional Plan Features (includes all Starter features plus)
            ['plan_id' => $professionalPlan->id, 'code' => 'pos_advanced', 'name' => 'Advanced POS', 'description' => 'Advanced POS with split bills', 'is_enabled' => true, 'value' => null, 'sort_order' => 1],
            ['plan_id' => $professionalPlan->id, 'code' => 'menu_management', 'name' => 'Menu Management', 'description' => 'Manage menu items and categories', 'is_enabled' => true, 'value' => null, 'sort_order' => 2],
            ['plan_id' => $professionalPlan->id, 'code' => 'order_management', 'name' => 'Order Management', 'description' => 'Create and manage orders', 'is_enabled' => true, 'value' => null, 'sort_order' => 3],
            ['plan_id' => $professionalPlan->id, 'code' => 'advanced_reports', 'name' => 'Advanced Reports', 'description' => 'Detailed analytics and reports', 'is_enabled' => true, 'value' => null, 'sort_order' => 4],
            ['plan_id' => $professionalPlan->id, 'code' => 'kitchen_display', 'name' => 'Kitchen Display', 'description' => 'Kitchen ticket display system', 'is_enabled' => true, 'value' => null, 'sort_order' => 5],
            ['plan_id' => $professionalPlan->id, 'code' => 'inventory_advanced', 'name' => 'Advanced Inventory', 'description' => 'Stock tracking with alerts', 'is_enabled' => true, 'value' => null, 'sort_order' => 6],
            ['plan_id' => $professionalPlan->id, 'code' => 'digital_menu', 'name' => 'Digital Menu', 'description' => 'QR code menu for customers', 'is_enabled' => true, 'value' => null, 'sort_order' => 7],
            ['plan_id' => $professionalPlan->id, 'code' => 'multi_location', 'name' => 'Multi Location', 'description' => 'Manage multiple locations', 'is_enabled' => false, 'value' => null, 'sort_order' => 8],
            ['plan_id' => $professionalPlan->id, 'code' => 'api_access', 'name' => 'API Access', 'description' => 'REST API integration', 'is_enabled' => false, 'value' => null, 'sort_order' => 9],
            ['plan_id' => $professionalPlan->id, 'code' => 'support_priority', 'name' => 'Priority Support', 'description' => 'Priority email and chat support', 'is_enabled' => true, 'value' => null, 'sort_order' => 10],

            // Enterprise Plan Features (includes all Professional features plus)
            ['plan_id' => $enterprisePlan->id, 'code' => 'pos_advanced', 'name' => 'Advanced POS', 'description' => 'Advanced POS with split bills', 'is_enabled' => true, 'value' => null, 'sort_order' => 1],
            ['plan_id' => $enterprisePlan->id, 'code' => 'menu_management', 'name' => 'Menu Management', 'description' => 'Manage menu items and categories', 'is_enabled' => true, 'value' => null, 'sort_order' => 2],
            ['plan_id' => $enterprisePlan->id, 'code' => 'order_management', 'name' => 'Order Management', 'description' => 'Create and manage orders', 'is_enabled' => true, 'value' => null, 'sort_order' => 3],
            ['plan_id' => $enterprisePlan->id, 'code' => 'advanced_reports', 'name' => 'Advanced Reports', 'description' => 'Detailed analytics and reports', 'is_enabled' => true, 'value' => null, 'sort_order' => 4],
            ['plan_id' => $enterprisePlan->id, 'code' => 'kitchen_display', 'name' => 'Kitchen Display', 'description' => 'Kitchen ticket display system', 'is_enabled' => true, 'value' => null, 'sort_order' => 5],
            ['plan_id' => $enterprisePlan->id, 'code' => 'inventory_advanced', 'name' => 'Advanced Inventory', 'description' => 'Stock tracking with alerts', 'is_enabled' => true, 'value' => null, 'sort_order' => 6],
            ['plan_id' => $enterprisePlan->id, 'code' => 'digital_menu', 'name' => 'Digital Menu', 'description' => 'QR code menu for customers', 'is_enabled' => true, 'value' => null, 'sort_order' => 7],
            ['plan_id' => $enterprisePlan->id, 'code' => 'multi_location', 'name' => 'Multi Location', 'description' => 'Manage multiple locations', 'is_enabled' => true, 'value' => null, 'sort_order' => 8],
            ['plan_id' => $enterprisePlan->id, 'code' => 'api_access', 'name' => 'API Access', 'description' => 'REST API integration', 'is_enabled' => true, 'value' => null, 'sort_order' => 9],
            ['plan_id' => $enterprisePlan->id, 'code' => 'support_dedicated', 'name' => 'Dedicated Support', 'description' => '24/7 dedicated support manager', 'is_enabled' => true, 'value' => null, 'sort_order' => 10],
            ['plan_id' => $enterprisePlan->id, 'code' => 'custom_integrations', 'name' => 'Custom Integrations', 'description' => 'Custom third-party integrations', 'is_enabled' => true, 'value' => null, 'sort_order' => 11],
            ['plan_id' => $enterprisePlan->id, 'code' => 'white_label', 'name' => 'White Label', 'description' => 'Custom branding options', 'is_enabled' => true, 'value' => null, 'sort_order' => 12],
        ];

        foreach ($features as $feature) {
            PlanFeature::updateOrCreate(
                [
                    'plan_id' => $feature['plan_id'],
                    'code' => $feature['code'],
                ],
                $feature
            );
        }
    }
}
