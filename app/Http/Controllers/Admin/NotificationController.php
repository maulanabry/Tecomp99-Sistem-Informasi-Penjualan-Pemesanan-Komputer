<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Display all notifications
     */
    public function index(Request $request)
    {
        /** @var \App\Models\Admin $admin */
        $admin = auth('admin')->user();

        if (!$admin) {
            return redirect()->route('login');
        }

        $query = SystemNotification::where('notifiable_id', $admin->id)
            ->where('notifiable_type', \App\Models\Admin::class)
            ->with('subject')
            ->latest();

        // Filter by read status
        if ($request->has('status')) {
            if ($request->status === 'unread') {
                $query->whereNull('read_at');
            } elseif ($request->status === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        // Filter by type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        $notifications = $query->paginate(20);
        $unreadCount = $this->notificationService->getUnreadCount($admin);

        return view('admin.notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        /** @var \App\Models\Admin $admin */
        $admin = auth('admin')->user();

        if (!$admin) {
            return redirect()->route('login');
        }

        $notification = SystemNotification::where('id', $id)
            ->where('notifiable_id', $admin->id)
            ->where('notifiable_type', \App\Models\Admin::class)
            ->first();

        if ($notification && !$notification->read_at) {
            $notification->update(['read_at' => now()]);
        }

        return back()->with('success', 'Notifikasi telah ditandai sebagai dibaca');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        /** @var \App\Models\Admin $admin */
        $admin = auth('admin')->user();

        if (!$admin) {
            return redirect()->route('login');
        }

        $this->notificationService->markAllAsRead($admin);

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca');
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        /** @var \App\Models\Admin $admin */
        $admin = auth('admin')->user();

        if (!$admin) {
            return redirect()->route('login');
        }

        $notification = SystemNotification::where('id', $id)
            ->where('notifiable_id', $admin->id)
            ->where('notifiable_type', \App\Models\Admin::class)
            ->first();

        if ($notification) {
            $notification->delete();
        }

        return back()->with('success', 'Notifikasi telah dihapus');
    }
}
