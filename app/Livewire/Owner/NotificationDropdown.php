<?php

namespace App\Livewire\Owner;

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
     * Mendapatkan notifikasi untuk pemilik saat ini
     */
    public function getNotifications()
    {
        $owner = auth('pemilik')->user();
        return SystemNotification::where('notifiable_id', $owner->id)
            ->where('notifiable_type', get_class($owner))
            ->latest()
            ->limit(10)
            ->get();
    }

    /**
     * Mendapatkan jumlah notifikasi yang belum dibaca untuk pemilik saat ini
     */
    public function getUnreadCount()
    {
        $owner = auth('pemilik')->user();
        return SystemNotification::where('notifiable_id', $owner->id)
            ->where('notifiable_type', get_class($owner))
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Menandai notifikasi sebagai sudah dibaca
     */
    public function markAsRead($notificationId)
    {
        $notification = SystemNotification::find($notificationId);
        $owner = auth('pemilik')->user();

        if ($notification && $notification->notifiable_id == $owner->id) {
            $notification->update(['read_at' => new DateTime()]);
        }

        $this->dispatch('refreshNotifications');
    }

    /**
     * Menandai semua notifikasi sebagai sudah dibaca
     */
    public function markAllAsRead()
    {
        $owner = auth('pemilik')->user();
        SystemNotification::where('notifiable_id', $owner->id)
            ->where('notifiable_type', get_class($owner))
            ->whereNull('read_at')
            ->update(['read_at' => new DateTime()]);

        $this->dispatch('refreshNotifications');
    }

    public function render()
    {
        return view('livewire.owner.notification-dropdown', [
            'notifications' => $this->getNotifications(),
            'unreadCount' => $this->getUnreadCount(),
        ]);
    }
}
