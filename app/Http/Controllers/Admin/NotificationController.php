<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\WaiterCall;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::withoutGlobalScopes()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $unreadCount = Notification::withoutGlobalScopes()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->where('read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::withoutGlobalScopes()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->findOrFail($id);

        $notification->update(['read' => true]);

        return response()->json(['success' => true]);
    }

    public function markRead($id)
    {
        return $this->markAsRead($id);
    }

    public function markAllRead()
    {
        return $this->markAllAsRead();
    }

    public function unreadCount()
    {
        $count = Notification::withoutGlobalScopes()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->where('read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function markAllAsRead()
    {
        Notification::withoutGlobalScopes()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->where('read', false)
            ->update(['read' => true]);

        return response()->json(['success' => true]);
    }

    public function getWaiterCalls()
    {
        $waiterCalls = WaiterCall::withoutGlobalScopes()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->where('status', 'pending')
            ->with(['table', 'attendedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'waiter_calls' => $waiterCalls,
        ]);
    }

    public function attendWaiterCall($id)
    {
        $waiterCall = WaiterCall::withoutGlobalScopes()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->findOrFail($id);

        $waiterCall->update([
            'status' => 'accepted',
            'attended_by' => auth()->id(),
            'attended_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function completeWaiterCall($id)
    {
        $waiterCall = WaiterCall::withoutGlobalScopes()
            ->where('tenant_id', auth()->user()->tenant_id)
            ->findOrFail($id);

        $waiterCall->update([
            'status' => 'completed',
        ]);

        return response()->json(['success' => true]);
    }
}
