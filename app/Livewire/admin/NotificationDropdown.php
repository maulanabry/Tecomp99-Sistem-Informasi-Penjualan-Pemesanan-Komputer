<?php

namespace App\Livewire\Admin;

use App\Models\SystemNotification;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use DateTime;

class NotificationDropdown extends Component
{
    protected $listeners = [
        'refreshNotifications' => '$refresh'
    ];

    /**
     * Get the notifications for the current admin
     */
    public function getNotifications()
    {
        $admin = auth('admin')->user();
        return SystemNotification::where('notifiable_id', $admin->id)
            ->where('notifiable_type', get_class($admin))
            ->latest()
            ->limit(10)
            ->get();
    }

    /**
     * Get the unread count for the current admin
     */
    public function getUnreadCount()
    {
        $admin = auth('admin')->user();
        return SystemNotification::where('notifiable_id', $admin->id)
            ->where('notifiable_type', get_class($admin))
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($notificationId)
    {
        $notification = SystemNotification::find($notificationId);
        $admin = auth('admin')->user();

        if ($notification && $notification->notifiable_id == $admin->id) {
            $notification->update(['read_at' => new DateTime()]);
        }

        $this->dispatch('refreshNotifications');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $admin = auth('admin')->user();
        SystemNotification::where('notifiable_id', $admin->id)
            ->where('notifiable_type', get_class($admin))
            ->whereNull('read_at')
            ->update(['read_at' => new DateTime()]);

        $this->dispatch('refreshNotifications');
    }

    public function render()
    {
        return view('livewire.admin.notification-dropdown', [
            'notifications' => $this->getNotifications(),
            'unreadCount' => $this->getUnreadCount(),
        ]);
    }
}
