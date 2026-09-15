<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index()
    {
        $currentTenant = auth()->user()?->tenant;
        $trialEndsAt = $currentTenant ? $currentTenant->trial_ends_at : null;
        $daysRemaining = $trialEndsAt ? round(now()->diffInDays($trialEndsAt, false)) : 0;
        
        // Get package name from subscription
        $packageName = 'Free Trial';
        $subscription = null;
        if ($currentTenant && $currentTenant->subscription) {
            $subscription = $currentTenant->subscription;
            $packageName = $currentTenant->subscription->plan->name ?? 'Free Trial';
        }
        
        return view('admin.billing.index', compact('currentTenant', 'daysRemaining', 'packageName', 'subscription'));
    }
}
