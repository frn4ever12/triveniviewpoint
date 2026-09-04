<?php

namespace App\Policies;

use App\Models\User;

class CheckoutPolicy
{
    public function view(User $user): bool
    {
        return $user->hasRole(['superadmin', 'admin', 'cashier']);
    }

    public function process(User $user): bool
    {
        return $user->hasRole(['superadmin', 'admin', 'cashier']);
    }
}
