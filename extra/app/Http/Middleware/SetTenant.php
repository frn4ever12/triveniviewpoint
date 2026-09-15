<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;

class SetTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip tenant resolution for super admin routes
        if ($request->is('admin/*') && Auth::check() && Auth::user()->hasRole('superadmin')) {
            return $next($request);
        }

        // Get tenant from authenticated user
        if (Auth::check() && Auth::user()->tenant_id) {
            $tenant = Tenant::find(Auth::user()->tenant_id);
            if ($tenant) {
                // Set tenant in session for easy access
                session(['tenant_id' => $tenant->id]);
                
                // Add tenant to request for controllers
                $request->merge(['tenant' => $tenant]);
            }
        }

        return $next($request);
    }
}
