<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $tenant = $user->tenant;

        // Get today's orders count
        $todayOrders = Order::whereDate('created_at', today())
            ->whereHas('table', function($query) use ($tenant) {
                if ($tenant) {
                    $query->where('tenant_id', $tenant->id);
                }
            })
            ->count();

        // Get today's revenue
        $todayRevenue = Order::whereDate('created_at', today())
            ->whereHas('table', function($query) use ($tenant) {
                if ($tenant) {
                    $query->where('tenant_id', $tenant->id);
                }
            })
            ->sum('total_amount');

        // Get active tables
        $activeTables = Table::where('status', 'occupied')
            ->when($tenant, function($query) use ($tenant) {
                return $query->where('tenant_id', $tenant->id);
            })
            ->count();

        // Get active staff (users with tenant)
        $activeStaff = User::where('tenant_id', $tenant->id ?? null)
            ->where('status', 'active')
            ->count();

        // Get recent orders
        $recentOrders = Order::with(['table'])
            ->whereHas('table', function($query) use ($tenant) {
                if ($tenant) {
                    $query->where('tenant_id', $tenant->id);
                }
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('mobile.dashboard', compact(
            'todayOrders',
            'todayRevenue',
            'activeTables',
            'activeStaff',
            'recentOrders'
        ));
    }
}
