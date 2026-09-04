<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Subscription;
use App\Models\User;
use App\Models\SubscriptionPlan;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_tenants' => Tenant::count(),
            'active_tenants' => Tenant::where('status', 'active')->count(),
            'pending_tenants' => Tenant::where('status', 'pending')->count(),
            'total_subscriptions' => Subscription::count(),
            'active_subscriptions' => Subscription::where('status', 'active')->count(),
            'total_users' => User::count(),
            'total_revenue' => Subscription::sum('amount'),
            'monthly_revenue' => Subscription::where('billing_cycle', 'monthly')->sum('amount'),
        ];

        $recentTenants = Tenant::latest()->take(5)->get();
        $recentSubscriptions = Subscription::with(['tenant', 'plan'])->latest()->take(5)->get();

        return view('superadmin.dashboard', compact('stats', 'recentTenants', 'recentSubscriptions'));
    }
}
