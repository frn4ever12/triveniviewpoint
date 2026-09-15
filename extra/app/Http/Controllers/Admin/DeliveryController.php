<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function index()
    {
        $tenantId = auth()->user()->tenant_id;

        // Get delivery orders by status
        $pendingOrders = Order::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('order_type', 'delivery')
            ->where('status', 'pending')
            ->with(['invoice', 'items'])
            ->get();

        $confirmedOrders = Order::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('order_type', 'delivery')
            ->where('status', 'confirmed')
            ->with(['invoice', 'items'])
            ->get();

        $preparingOrders = Order::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('order_type', 'delivery')
            ->where('status', 'preparing')
            ->with(['invoice', 'items'])
            ->get();

        $readyOrders = Order::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('order_type', 'delivery')
            ->where('status', 'ready')
            ->with(['invoice', 'items'])
            ->get();

        $onTheWayOrders = Order::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('order_type', 'delivery')
            ->where('status', 'on_the_way')
            ->with(['invoice', 'items'])
            ->get();

        $deliveredOrders = Order::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('order_type', 'delivery')
            ->where('status', 'delivered')
            ->whereDate('created_at', today())
            ->with(['invoice', 'items'])
            ->get();

        // Calculate totals for each status
        $pendingTotal = $pendingOrders->sum(function($order) {
            return $order->invoice?->total_amount ?? 0;
        });
        $confirmedTotal = $confirmedOrders->sum(function($order) {
            return $order->invoice?->total_amount ?? 0;
        });
        $preparingTotal = $preparingOrders->sum(function($order) {
            return $order->invoice?->total_amount ?? 0;
        });
        $readyTotal = $readyOrders->sum(function($order) {
            return $order->invoice?->total_amount ?? 0;
        });
        $onTheWayTotal = $onTheWayOrders->sum(function($order) {
            return $order->invoice?->total_amount ?? 0;
        });
        $deliveredTotal = $deliveredOrders->sum(function($order) {
            return $order->invoice?->total_amount ?? 0;
        });

        // Get active delivery orders (all except delivered and cancelled)
        $activeOrders = Order::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('order_type', 'delivery')
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'assigned', 'picked_up', 'on_the_way'])
            ->with(['invoice', 'items'])
            ->latest()
            ->get();

        // Get delivery drivers (users with delivery role or specific permission)
        $drivers = User::where('tenant_id', $tenantId)
            ->where(function($query) {
                $query->where('role', 'delivery')
                    ->orWhere('role', 'driver');
            })
            ->get();

        return view('admin.delivery.index', compact(
            'pendingOrders',
            'confirmedOrders',
            'preparingOrders',
            'readyOrders',
            'onTheWayOrders',
            'deliveredOrders',
            'pendingTotal',
            'confirmedTotal',
            'preparingTotal',
            'readyTotal',
            'onTheWayTotal',
            'deliveredTotal',
            'activeOrders',
            'drivers'
        ));
    }
}
