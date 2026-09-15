<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::with('planFeatures')->ordered()->paginate(20);
        $view = request()->routeIs('superadmin.*') ? 'superadmin.subscription-plans.index' : 'admin.subscription-plans.index';
        return view($view, compact('plans'));
    }

    public function create()
    {
        $view = request()->routeIs('superadmin.*') ? 'superadmin.subscription-plans.create' : 'admin.subscription-plans.create';
        return view($view);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'monthly_price' => 'required|numeric|min:0',
            'yearly_price' => 'required|numeric|min:0',
            'trial_days' => 'required|integer|min:0',
            'max_users' => 'required|integer|min:1',
            'max_menu_items' => 'required|integer|min:1',
            'max_orders_per_month' => 'nullable|integer|min:0',
            'modules' => 'nullable|array',
            'modules.*' => 'string|in:orders,pos,categories,menu_items,tables,customers,reports,inventory,digital_menu,staff,settings',
            'sort_order' => 'required|integer|min:0',
        ]);

        // Handle checkbox values manually
        $validated['is_active'] = $request->has('is_active');
        $validated['is_popular'] = $request->has('is_popular');
        $validated['modules'] = $request->input('modules', []);

        try {
            SubscriptionPlan::create($validated);
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Failed to create plan: ' . $e->getMessage());
        }

        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.subscription-plans.index' : 'admin.subscription-plans.index';
        return redirect()->route($redirectRoute)->with('success', 'Subscription plan created successfully.');
    }

    public function show(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->load('planFeatures');
        $view = request()->routeIs('superadmin.*') ? 'superadmin.subscription-plans.show' : 'admin.subscription-plans.show';
        return view($view, compact('subscriptionPlan'));
    }

    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->load('planFeatures');
        $view = request()->routeIs('superadmin.*') ? 'superadmin.subscription-plans.edit' : 'admin.subscription-plans.edit';
        return view($view, compact('subscriptionPlan'));
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'monthly_price' => 'required|numeric|min:0',
            'yearly_price' => 'required|numeric|min:0',
            'trial_days' => 'required|integer|min:0',
            'max_users' => 'required|integer|min:1',
            'max_menu_items' => 'required|integer|min:1',
            'max_orders_per_month' => 'nullable|integer|min:0',
            'modules' => 'nullable|array',
            'modules.*' => 'string|in:orders,pos,categories,menu_items,tables,customers,reports,inventory,digital_menu,staff,settings',
            'sort_order' => 'required|integer|min:0',
        ]);

        // Handle checkbox values manually
        $validated['is_active'] = $request->has('is_active');
        $validated['is_popular'] = $request->has('is_popular');
        $validated['modules'] = $request->input('modules', []);

        $subscriptionPlan->update($validated);

        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.subscription-plans.index' : 'admin.subscription-plans.index';
        return redirect()->route($redirectRoute)->with('success', 'Subscription plan updated successfully.');
    }

    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->delete();
        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.subscription-plans.index' : 'admin.subscription-plans.index';
        return redirect()->route($redirectRoute)->with('success', 'Subscription plan deleted successfully.');
    }
}
