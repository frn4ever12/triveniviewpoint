<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@dmcrestro.com'],
            [
                'name' => 'Super Admin',
                'phone' => '9800000000',
                'password' => Hash::make('Admin@123'),
                'status' => 'active',
            ]
        );
        $superAdmin->assignRole('superadmin');

        $admin = User::updateOrCreate(
            ['email' => 'admin@dmcrestro.com'],
            [
                'name' => 'Admin',
                'phone' => '9800000001',
                'password' => Hash::make('Admin@123'),
                'status' => 'active',
            ]
        );
        $admin->assignRole('admin');

        $chef = User::updateOrCreate(
            ['email' => 'chef@dmcrestro.com'],
            [
                'name' => 'Chef',
                'phone' => '9800000002',
                'password' => Hash::make('Admin@123'),
                'status' => 'active',
            ]
        );
        $chef->assignRole('chef');

        $waiter = User::updateOrCreate(
            ['email' => 'waiter@dmcrestro.com'],
            [
                'name' => 'Waiter',
                'phone' => '9800000003',
                'password' => Hash::make('Admin@123'),
                'status' => 'active',
            ]
        );
        $waiter->assignRole('waiter');

        $cashier = User::updateOrCreate(
            ['email' => 'cashier@dmcrestro.com'],
            [
                'name' => 'Cashier',
                'phone' => '9800000004',
                'password' => Hash::make('Admin@123'),
                'status' => 'active',
            ]
        );
        $cashier->assignRole('cashier');
    }
}
