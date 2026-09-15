<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ── Module-based permissions ─────────────────────────────────
        $permissions = [
            // Dashboard
            'dashboard.view',

            // Orders
            'orders.view',
            'orders.create',
            'orders.edit',
            'orders.delete',
            'orders.checkout',
            'orders.cancel',

            // POS
            'pos.access',
            'pos.create',
            'pos.checkout',

            // Menu & Categories
            'menus.view',
            'menus.create',
            'menus.edit',
            'menus.delete',

            // Dishes
            'dishes.view',
            'dishes.create',
            'dishes.edit',
            'dishes.delete',

            // Tables
            'tables.view',
            'tables.create',
            'tables.edit',
            'tables.delete',

            // Inventory
            'products.view',
            'products.create',
            'products.edit',
            'products.delete',
            'purchases.view',
            'purchases.create',
            'purchases.edit',
            'purchases.delete',

            // Expenses
            'expenses.view',
            'expenses.create',
            'expenses.edit',
            'expenses.delete',

            // Reports
            'reports.view',

            // Suppliers
            'suppliers.view',
            'suppliers.create',
            'suppliers.edit',
            'suppliers.delete',

            // Staff
            'staff.view',
            'staff.create',
            'staff.edit',
            'staff.delete',

            // Roles & Permissions (admin/superadmin only)
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            // Website Settings
            'settings.view',
            'settings.edit',

            // KOT / Kitchen
            'kot.view',
            'kot.print',

            // Labels, Banners, Contacts, About
            'labels.view',
            'labels.create',
            'labels.edit',
            'labels.delete',
            'banners.view',
            'banners.create',
            'banners.edit',
            'banners.delete',
            'contacts.view',
            'contacts.manage',
            'abouts.view',
            'abouts.edit',

            // Units
            'units.view',
            'units.create',
            'units.edit',
            'units.delete',

            // Rooms
            'rooms.view',
            'rooms.create',
            'rooms.edit',
            'rooms.delete',

            // Digital Menu
            'digital-menu.view',
        ];

        // Rooms are admin-only, no need to add to cashier/waiter

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }

        $allPermissions = Permission::all();

        // ── Roles ────────────────────────────────────────────────────
        // Superadmin – full access
        $superadmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $superadmin->syncPermissions($allPermissions);

        // Admin (restaurant owner) – almost full access except role management
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions($allPermissions);

        // Chef – KTD only (kitchen display)
        $chef = Role::firstOrCreate(['name' => 'chef', 'guard_name' => 'web']);
        $chef->syncPermissions([
            'kot.view',
            'kot.print',
        ]);

        // Cashier – POS & checkout
        $cashier = Role::firstOrCreate(['name' => 'cashier', 'guard_name' => 'web']);
        $cashier->syncPermissions([
            'orders.view',
            'orders.create',
            'orders.checkout',
            'orders.cancel',
            'pos.access',
            'pos.create',
            'pos.checkout',
            'kot.view',
            'kot.print',
        ]);

        // Waiter – order taking, POS, KTD
        $waiter = Role::firstOrCreate(['name' => 'waiter', 'guard_name' => 'web']);
        $waiter->syncPermissions([
            'orders.view',
            'orders.create',
            'pos.access',
            'pos.create',
            'kot.view',
            'kot.print',
        ]);

        // Clean up old roles that no longer apply
        foreach (['manager', 'counter'] as $oldRole) {
            $role = Role::where('name', $oldRole)->first();
            if ($role) {
                $role->delete();
            }
        }
    }
}
