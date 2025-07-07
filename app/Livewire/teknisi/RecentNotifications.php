<?php

namespace App\Livewire\Teknisi;

use App\Models\SystemNotification;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RecentNotifications extends Component
{
    public $showAll = false;
    public $limit = 5;

    /**
     * Toggle show all notifications
     */
    public function toggleShowAll()
    {
        $this->showAll = !$this->showAll;
    }

    /**
     * Get recent notifications for the technician
     */
    public function getRecentNotifications()
    {
        $teknisiId = Auth::guard('teknisi')->id();

        $query = SystemNotification::where('notifiable_id', $teknisiId)
            ->where('notifiable_type', 'App\Models\Admin')
            ->orderBy('created_at', 'desc');

        if (!$this->showAll) {
            $query->limit($this->limit);
        }

        return $query->get()->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->message, // Use message as title since there's no title field
                'message' => $notification->message,
                'type' => $notification->type,
                'data' => $notification->data,
                'is_read' => !$notification->isUnread(), // Use the model method
                'created_at' => $notification->created_at,
                'time_ago' => $this->getTimeAgo($notification->created_at),
                'icon' => $this->getNotificationIcon($notification->type),
                'color' => $this->getNotificationColor($notification->type),
            ];
        });
    }

    /**
     * Get notification icon based on type
     */
    private function getNotificationIcon($type)
    {
        switch ($type) {
            case 'ticket_assigned':
                return 'fas fa-ticket-alt';
            case 'schedule_reminder':
                return 'fas fa-calendar-check';
            case 'status_update':
                return 'fas fa-sync-alt';
            case 'overdue_alert':
                return 'fas fa-exclamation-triangle';
            case 'customer_message':
                return 'fas fa-comment';
            case 'system_update':
                return 'fas fa-cog';
            default:
                return 'fas fa-bell';
        }
    }

    /**
     * Get notification color based on type
     */
    private function getNotificationColor($type)
    {
        switch ($type) {
            case 'ticket_assigned':
                return 'blue';
            case 'schedule_reminder':
                return 'green';
            case 'status_update':
                return 'purple';
            case 'overdue_alert':
                return 'red';
            case 'customer_message':
                return 'yellow';
            case 'system_update':
                return 'gray';
            default:
                return 'blue';
        }
    }

    /**
     * Get human readable time ago
     */
    private function getTimeAgo($datetime)
    {
        $carbon = Carbon::parse($datetime);

        if ($carbon->isToday()) {
            return $carbon->format('H:i');
        } elseif ($carbon->isYesterday()) {
            return 'Kemarin ' . $carbon->format('H:i');
        } elseif ($carbon->diffInDays() <= 7) {
            return $carbon->diffInDays() . ' hari lalu';
        }

        return $carbon->format('d/m/Y');
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId)
    {
        $notification = SystemNotification::find($notificationId);
        if ($notification && $notification->notifiable_id == Auth::guard('teknisi')->id()) {
            $notification->markAsRead(); // Use the model method
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        SystemNotification::where('notifiable_id', Auth::guard('teknisi')->id())
            ->where('notifiable_type', 'App\Models\Admin')
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        session()->flash('message', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    /**
     * Navigate to notification details or related page
     */
    public function viewNotification($notificationId)
    {
        $this->markAsRead($notificationId);

        $notification = SystemNotification::find($notificationId);
        if ($notification && isset($notification->data['action_url'])) {
            return redirect($notification->data['action_url']);
        }

        return redirect()->route('teknisi.notifications.index');
    }

    /**
     * Navigate to all notifications
     */
    public function viewAllNotifications()
    {
        return redirect()->route('teknisi.notifications.index');
    }

    public function render()
    {
        $teknisiId = Auth::guard('teknisi')->id();
        $notifications = $this->getRecentNotifications();

        $unreadCount = SystemNotification::where('notifiable_id', $teknisiId)
            ->where('notifiable_type', 'App\Models\Admin')
            ->whereNull('read_at')
            ->count();

        $totalNotifications = SystemNotification::where('notifiable_id', $teknisiId)
            ->where('notifiable_type', 'App\Models\Admin')
            ->count();

        return view('livewire.teknisi.recent-notifications', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'totalNotifications' => $totalNotifications,
            'hasMore' => $totalNotifications > $this->limit && !$this->showAll
        ]);
    }
}
