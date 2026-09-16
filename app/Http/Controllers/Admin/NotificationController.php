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
        $notifications = Notification::where('tenant_id', auth()->user()->tenant_id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $unreadCount = Notification::where('tenant_id', auth()->user()->tenant_id)
            ->where('read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::where('tenant_id', auth()->user()->tenant_id)
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
        $count = Notification::where('tenant_id', auth()->user()->tenant_id)
            ->where('read', false)
            ->count();

        return response()->json(['count' => $count]);
    }

    public function markAllAsRead()
    {
        Notification::where('tenant_id', auth()->user()->tenant_id)
            ->where('read', false)
            ->update(['read' => true]);

        return response()->json(['success' => true]);
    }

    public function getWaiterCalls()
    {
        $waiterCalls = WaiterCall::where('tenant_id', auth()->user()->tenant_id)
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
        $waiterCall = WaiterCall::where('tenant_id', auth()->user()->tenant_id)
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
        $waiterCall = WaiterCall::where('tenant_id', auth()->user()->tenant_id)
            ->findOrFail($id);

        $waiterCall->update([
            'status' => 'completed',
        ]);

        return response()->json(['success' => true]);
    }

    public function testCreate(Request $request)
    {
        try {
            \Log::info('Test notification creation started', [
                'tenant_id' => auth()->user()->tenant_id,
                'user_id' => auth()->id(),
            ]);

            $notification = Notification::create([
                'tenant_id' => auth()->user()->tenant_id,
                'type' => 'test',
                'title' => 'Test Notification',
                'message' => 'This is a test notification created at ' . now()->format('H:i:s'),
                'data' => json_encode(['test' => true]),
                'read' => false,
            ]);

            \Log::info('Test notification created successfully', [
                'notification_id' => $notification->id,
                'tenant_id' => $notification->tenant_id,
            ]);

            return response()->json([
                'success' => true,
                'notification' => $notification,
                'tenant_id' => auth()->user()->tenant_id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Test notification creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function debug()
    {
        try {
            $tableExists = \Schema::hasTable('notifications');
            $totalNotifications = Notification::count();
            $tenantNotifications = Notification::where('tenant_id', auth()->user()->tenant_id)->count();
            $unreadNotifications = Notification::where('tenant_id', auth()->user()->tenant_id)->where('read', false)->count();
            
            $recentNotifications = Notification::where('tenant_id', auth()->user()->tenant_id)
                ->latest()
                ->take(5)
                ->get();

            return response()->json([
                'table_exists' => $tableExists,
                'total_notifications' => $totalNotifications,
                'tenant_notifications' => $tenantNotifications,
                'unread_notifications' => $unreadNotifications,
                'user_tenant_id' => auth()->user()->tenant_id,
                'recent_notifications' => $recentNotifications,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }
}
