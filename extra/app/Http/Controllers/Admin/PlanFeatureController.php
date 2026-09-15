<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlanFeature;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class PlanFeatureController extends Controller
{
    public function index($planId)
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        $features = PlanFeature::where('plan_id', $planId)->ordered()->get();
        $view = request()->routeIs('superadmin.*') ? 'superadmin.plan-features.index' : 'admin.plan-features.index';
        return view($view, compact('plan', 'features'));
    }

    public function create($planId)
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        $view = request()->routeIs('superadmin.*') ? 'superadmin.plan-features.create' : 'admin.plan-features.create';
        return view($view, compact('plan'));
    }

    public function store(Request $request, $planId)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:100|unique:plan_features,code,NULL,id,plan_id,' . $planId,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_enabled' => 'boolean',
            'value' => 'nullable|string',
            'sort_order' => 'required|integer|min:0',
        ]);

        PlanFeature::create([
            'plan_id' => $planId,
            'code' => $validated['code'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'is_enabled' => $validated['is_enabled'] ?? true,
            'value' => $validated['value'],
            'sort_order' => $validated['sort_order'],
        ]);

        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.plan-features.index' : 'admin.plan-features.index';
        return redirect()->route($redirectRoute, $planId)->with('success', 'Plan feature created successfully.');
    }

    public function edit(PlanFeature $planFeature)
    {
        $plan = $planFeature->plan;
        $view = request()->routeIs('superadmin.*') ? 'superadmin.plan-features.edit' : 'admin.plan-features.edit';
        return view($view, compact('plan', 'planFeature'));
    }

    public function update(Request $request, PlanFeature $planFeature)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:100|unique:plan_features,code,' . $planFeature->id . ',id,plan_id,' . $planFeature->plan_id,
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_enabled' => 'boolean',
            'value' => 'nullable|string',
            'sort_order' => 'required|integer|min:0',
        ]);

        $planFeature->update($validated);

        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.plan-features.index' : 'admin.plan-features.index';
        return redirect()->route($redirectRoute, $planFeature->plan_id)->with('success', 'Plan feature updated successfully.');
    }

    public function destroy(PlanFeature $planFeature)
    {
        $planId = $planFeature->plan_id;
        $planFeature->delete();
        $redirectRoute = request()->routeIs('superadmin.*') ? 'superadmin.plan-features.index' : 'admin.plan-features.index';
        return redirect()->route($redirectRoute, $planId)->with('success', 'Plan feature deleted successfully.');
    }
}
