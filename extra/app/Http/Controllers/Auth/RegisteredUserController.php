<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Display the restaurant registration view with subscription plans.
     */
    public function createRestaurant(): View
    {
        $plans = SubscriptionPlan::active()->ordered()->get();
        return view('auth.register-restaurant', compact('plans'));
    }

    /**
     * Handle an incoming restaurant registration request with subscription.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeRestaurant(Request $request): RedirectResponse
    {
        $request->validate([
            // Tenant Information
            'restaurant_name' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:tenants,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'pan_no' => ['nullable', 'string', 'max:20'],
            
            // Subscription Plan
            'plan_id' => ['required', 'exists:subscription_plans,id'],
            'billing_cycle' => ['required', 'in:monthly,yearly'],
            
            // Admin User Information
            'admin_name' => ['required', 'string', 'max:255'],
            'admin_email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create Tenant
        $tenant = Tenant::create([
            'name' => $request->restaurant_name,
            'slug' => strtolower(str_replace(' ', '-', $request->restaurant_name)) . '-' . time(),
            'company_name' => $request->company_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'country' => $request->country,
            'pan_no' => $request->pan_no,
            'status' => 'pending', // Set to pending for superadmin approval
        ]);

        // Create Subscription
        $plan = SubscriptionPlan::find($request->plan_id);
        $amount = $request->billing_cycle === 'yearly' ? $plan->yearly_price : $plan->monthly_price;
        $endsAt = $request->billing_cycle === 'yearly' 
            ? now()->addYear() 
            : now()->addMonth();

        $subscription = Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'billing_cycle' => $request->billing_cycle,
            'amount' => $amount,
            'starts_at' => now(),
            'ends_at' => $plan->trial_days > 0 ? now()->addDays($plan->trial_days) : $endsAt,
            'next_billing_at' => $plan->trial_days > 0 ? now()->addDays($plan->trial_days) : $endsAt,
            'status' => $plan->trial_days > 0 ? 'trialing' : 'active',
        ]);

        // Update tenant trial end date if applicable
        if ($plan->trial_days > 0) {
            $tenant->update(['trial_ends_at' => now()->addDays($plan->trial_days)]);
        }

        // Create Admin User
        $user = User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => Hash::make($request->password),
            'tenant_id' => $tenant->id,
            'status' => 'active',
        ]);
        $user->assignRole('admin');

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Your restaurant has been registered successfully!');
    }
}
