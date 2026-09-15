<?php

namespace App\Providers;

use App\Policies\CheckoutPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::define('checkout-view', [CheckoutPolicy::class, 'view']);
        Gate::define('checkout-process', [CheckoutPolicy::class, 'process']);
    }
}
