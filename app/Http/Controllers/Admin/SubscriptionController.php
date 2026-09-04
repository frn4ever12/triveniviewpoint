<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['tenant', 'plan'])->latest()->paginate(20);
        $view = request()->routeIs('superadmin.*') ? 'superadmin.subscriptions.index' : 'admin.subscriptions.index';
        return view($view, compact('subscriptions'));
    }

    public function create()
    {
        $tenants = \App\Models\Tenant::active()->get();
        $plans = SubscriptionPlan::active()->ordered()->get();
        $view = request()->routeIs('superadmin.*') ? 'superadmin.subscriptions.create' : 'admin.subscriptions.create';
        return view($view, compact('tenants', 'plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'plan_id' => 'required|exists:subscription_plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'starts_at' => 'required|date',
        ]);

        $plan = SubscriptionPlan::find($validated['plan_id']);
        $amount = $validated['billing_cycle'] === 'yearly' ? $plan->yearly_price : $plan->monthly_price;
        $endsAt = $validated['billing_cycle'] === 'yearly' 
            ? Carbon::parse($validated['starts_at'])->addYear() 
            : Carbon::parse($validated['starts_at'])->addMonth();

        Subscription::create([
            'tenant_id' => $validated['tenant_id'],
            'plan_id' => $validated['plan_id'],
            'billing_cycle' => $validated['billing_cycle'],
            'amount' => $amount,
            'starts_at' => $validated['starts_at'],
            'ends_at' => $endsAt,
            'next_billing_at' => $endsAt,
            'status' => 'active',
        ]);

        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.subscriptions.index' : 'admin.subscriptions.index';
        return redirect()->route($redirectRoute)->with('success', 'Subscription created successfully.');
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['tenant', 'plan']);
        $view = request()->routeIs('superadmin.*') ? 'superadmin.subscriptions.show' : 'admin.subscriptions.show';
        return view($view, compact('subscription'));
    }

    public function edit(Subscription $subscription)
    {
        $subscription->load(['tenant', 'plan']);
        $plans = SubscriptionPlan::active()->ordered()->get();
        $view = request()->routeIs('superadmin.*') ? 'superadmin.subscriptions.edit' : 'admin.subscriptions.edit';
        return view($view, compact('subscription', 'plans'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'status' => 'required|in:active,trialing,past_due,cancelled,expired',
            'payment_method' => 'nullable|string',
            'payment_id' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $plan = SubscriptionPlan::find($validated['plan_id']);
        $amount = $validated['billing_cycle'] === 'yearly' ? $plan->yearly_price : $plan->monthly_price;

        $subscription->update([
            'plan_id' => $validated['plan_id'],
            'billing_cycle' => $validated['billing_cycle'],
            'amount' => $amount,
            'status' => $validated['status'],
            'payment_method' => $validated['payment_method'],
            'payment_id' => $validated['payment_id'],
            'notes' => $validated['notes'],
        ]);

        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.subscriptions.index' : 'admin.subscriptions.index';
        return redirect()->route($redirectRoute)->with('success', 'Subscription updated successfully.');
    }

    public function cancel(Subscription $subscription)
    {
        $subscription->update(['status' => 'cancelled']);
        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.subscriptions.index' : 'admin.subscriptions.index';
        return redirect()->route($redirectRoute)->with('success', 'Subscription cancelled successfully.');
    }

    public function renew(Subscription $subscription)
    {
        $plan = $subscription->plan;
        $newEndsAt = $subscription->billing_cycle === 'yearly' 
            ? now()->addYear() 
            : now()->addMonth();

        $subscription->update([
            'status' => 'active',
            'ends_at' => $newEndsAt,
            'next_billing_at' => $newEndsAt,
        ]);

        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.subscriptions.index' : 'admin.subscriptions.index';
        return redirect()->route($redirectRoute)->with('success', 'Subscription renewed successfully.');
    }
}
