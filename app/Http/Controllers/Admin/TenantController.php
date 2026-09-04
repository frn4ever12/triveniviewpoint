<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::with(['subscription.plan', 'users'])->latest()->paginate(20);
        $view = request()->routeIs('superadmin.*') ? 'superadmin.tenants.index' : 'admin.tenants.index';
        return view($view, compact('tenants'));
    }

    public function create()
    {
        $plans = SubscriptionPlan::active()->ordered()->get();
        $view = request()->routeIs('superadmin.*') ? 'superadmin.tenants.create' : 'admin.tenants.create';
        return view($view, compact('plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:tenants',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pan_no' => 'nullable|string|max:20',
            'domain' => 'nullable|string|unique:tenants',
            'plan_id' => 'required|exists:subscription_plans,id',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users',
            'admin_password' => 'required|string|min:8',
        ]);

        $tenant = Tenant::create([
            'name' => $validated['name'],
            'company_name' => $validated['company_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'city' => $validated['city'],
            'country' => $validated['country'],
            'pan_no' => $validated['pan_no'],
            'domain' => $validated['domain'],
            'status' => 'active',
        ]);

        // Create subscription
        $plan = SubscriptionPlan::find($validated['plan_id']);
        $subscription = Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'billing_cycle' => 'monthly',
            'amount' => $plan->monthly_price,
            'starts_at' => now(),
            'ends_at' => $plan->trial_days > 0 ? now()->addDays($plan->trial_days) : now()->addMonth(),
            'next_billing_at' => $plan->trial_days > 0 ? now()->addDays($plan->trial_days) : now()->addMonth(),
            'status' => $plan->trial_days > 0 ? 'trialing' : 'active',
        ]);

        // Update tenant trial end date
        if ($plan->trial_days > 0) {
            $tenant->update(['trial_ends_at' => now()->addDays($plan->trial_days)]);
        }

        // Create admin user for tenant
        $admin = User::create([
            'name' => $validated['admin_name'],
            'email' => $validated['admin_email'],
            'password' => Hash::make($validated['admin_password']),
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);
        $admin->assignRole('admin');

        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.tenants.index' : 'admin.tenants.index';
        return redirect()->route($redirectRoute)->with('success', 'Tenant created successfully.');
    }

    public function show(Tenant $tenant)
    {
        $tenant->load(['subscription.plan', 'users', 'subscriptions']);
        $view = request()->routeIs('superadmin.*') ? 'superadmin.tenants.show' : 'admin.tenants.show';
        return view($view, compact('tenant'));
    }

    public function edit(Tenant $tenant)
    {
        $plans = SubscriptionPlan::active()->ordered()->get();
        $view = request()->routeIs('superadmin.*') ? 'superadmin.tenants.edit' : 'admin.tenants.edit';
        return view($view, compact('tenant', 'plans'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:tenants,email,' . $tenant->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'pan_no' => 'nullable|string|max:20',
            'domain' => 'nullable|string|unique:tenants,domain,' . $tenant->id,
            'status' => 'required|in:active,inactive,suspended',
        ]);

        $tenant->update($validated);

        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.tenants.index' : 'admin.tenants.index';
        return redirect()->route($redirectRoute)->with('success', 'Tenant updated successfully.');
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.tenants.index' : 'admin.tenants.index';
        return redirect()->route($redirectRoute)->with('success', 'Tenant deleted successfully.');
    }

    public function approve(Tenant $tenant)
    {
        $tenant->update(['status' => 'active']);
        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.tenants.index' : 'admin.tenants.index';
        return redirect()->route($redirectRoute)->with('success', 'Tenant approved successfully.');
    }

    public function reject(Tenant $tenant)
    {
        $tenant->update(['status' => 'rejected']);
        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.tenants.index' : 'admin.tenants.index';
        return redirect()->route($redirectRoute)->with('success', 'Tenant rejected successfully.');
    }

    public function suspend(Tenant $tenant)
    {
        $tenant->update(['status' => 'suspended']);
        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.tenants.index' : 'admin.tenants.index';
        return redirect()->route($redirectRoute)->with('success', 'Tenant suspended successfully.');
    }

    public function activate(Tenant $tenant)
    {
        $tenant->update(['status' => 'active']);
        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.tenants.index' : 'admin.tenants.index';
        return redirect()->route($redirectRoute)->with('success', 'Tenant activated successfully.');
    }
}
