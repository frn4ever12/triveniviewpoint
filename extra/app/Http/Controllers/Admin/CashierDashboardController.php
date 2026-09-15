<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Table;

class CashierDashboardController extends Controller
{
    public function index()
    {
        $tables = Table::with(['orders' => function ($q) {
            $q->whereNotIn('status', ['completed', 'cancelled'])
                ->with(['items.menuItem', 'waiter', 'invoice'])
                ->latest();
        }])->orderBy('name')->get();

        $occupiedCount = $tables->filter(fn($t) => ($t->status->value ?? $t->status) === 'occupied')->count();

        $todayOrders = Order::whereDate('created_at', today())->count();

        $todayRevenue = Order::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->with('invoice')
            ->get()
            ->sum(fn($o) => $o->invoice?->total_amount ?? 0);

        $pendingOrders = Order::with(['table', 'waiter', 'invoice', 'items'])
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->whereDate('created_at', today())
            ->latest()
            ->take(50)
            ->get();

        $siteName = app(\App\Services\WebsiteSettingService::class)->get('site_name', 'Restaurant');

        return view('admin.order.checkout-dashboard', compact(
            'tables', 'occupiedCount', 'todayOrders', 'todayRevenue',
            'pendingOrders', 'siteName'
        ));
    }
}
