<?php

use App\Models\Tenant;
use App\Models\User;
use Spatie\Permission\Models\Permission;

it('shows the add new order button and modal on the orders page', function () {
    Permission::findOrCreate('orders.view');

    $tenant = Tenant::query()->create([
        'name' => 'Test Restaurant',
        'email' => 'tenant@example.com',
    ]);

    $user = User::factory()->create([
        'tenant_id' => $tenant->id,
        'login_enabled' => true,
    ]);
    $user->givePermissionTo('orders.view');

    $this->actingAs($user)
        ->get(route('admin.orders.index'))
        ->assertOk()
        ->assertSee('Add New Order')
        ->assertSee('id="addOrderModal"', false)
        ->assertSee('openAddOrderModal', false);
});
