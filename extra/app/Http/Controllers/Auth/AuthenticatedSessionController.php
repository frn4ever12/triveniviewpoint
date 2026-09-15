<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Check for custom redirect (from mobile login)
        if ($request->has('redirect_to')) {
            return redirect()->to($request->input('redirect_to'))->with('success', 'Login Successfully!');
        }

        if ($user) {
            $role = $user->getRoleNames()->first();

            $redirectRoute = match ($role) {
                'superadmin' => route('superadmin.dashboard', absolute: false),
                'admin' => route('dashboard', absolute: false),
                'waiter' => route('admin.orders.pos', absolute: false),
                'cashier' => route('admin.orders.checkout-dashboard', absolute: false),
                'chef' => route('admin.kitchen-display.index', absolute: false),
                default => route('dashboard', absolute: false),
            };

            return redirect()->intended($redirectRoute)->with('success', 'Login Successfully!');
        }

        return redirect()->intended(route('dashboard', absolute: false))->with('success', 'Login Successfully!');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged Out Successfully');
    }
}
