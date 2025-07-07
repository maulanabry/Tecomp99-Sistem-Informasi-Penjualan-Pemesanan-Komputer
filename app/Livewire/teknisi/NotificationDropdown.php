<?php

namespace App\Livewire\Teknisi;

use App\Models\SystemNotification;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use DateTime;

class NotificationDropdown extends Component
{
    protected $listeners = [
        'refreshNotifications' => '$refresh',
        'notification-updated' => '$refresh'
    ];

    /**
     * Get the notifications for the current teknisi
     */
    public function getNotifications()
    {
        $teknisi = auth('teknisi')->user();
        return SystemNotification::where('notifiable_id', $teknisi->id)
            ->where('notifiable_type', get_class($teknisi))
            ->latest()
            ->limit(10)
            ->get();
    }

    /**
     * Get the unread count for the current teknisi
     */
    public function getUnreadCount()
    {
        $teknisi = auth('teknisi')->user();
        return SystemNotification::where('notifiable_id', $teknisi->id)
            ->where('notifiable_type', get_class($teknisi))
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Mark a notification as read
     */
    public function markAsRead($notificationId)
    {
        $notification = SystemNotification::find($notificationId);
        $teknisi = auth('teknisi')->user();

        if ($notification && $notification->notifiable_id == $teknisi->id) {
            $notification->update(['read_at' => new DateTime()]);
        }

        $this->dispatch('refreshNotifications');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $teknisi = auth('teknisi')->user();
        SystemNotification::where('notifiable_id', $teknisi->id)
            ->where('notifiable_type', get_class($teknisi))
            ->whereNull('read_at')
            ->update(['read_at' => new DateTime()]);

        $this->dispatch('refreshNotifications');
    }

    public function render()
    {
        return view('livewire.teknisi.notification-dropdown', [
            'notifications' => $this->getNotifications(),
            'unreadCount' => $this->getUnreadCount(),
        ]);
    }
}
