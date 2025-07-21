<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\SystemNotification;
use App\Models\Customer;

class NotificationDropdown extends Component
{
    public $unreadCount = 0;
    public $notifications;

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        /** @var Customer $customer */
        $customer = auth('customer')->user();
        if (!$customer) return;

        $this->notifications = $customer->notifications()
            ->latest()
            ->limit(5)
            ->get();

        $this->unreadCount = $customer->unread_notifications_count;
    }

    public function markAsRead($notificationId)
    {
        /** @var Customer $customer */
        $customer = auth('customer')->user();
        if (!$customer) return;

        $notification = $customer->notifications()
            ->where('id', $notificationId)
            ->first();

        if ($notification && !$notification->read_at) {
            $notification->markAsRead();
            $this->loadNotifications();

            // Navigate to the notification URL if available
            if (isset($notification->data['action_url'])) {
                return redirect($notification->data['action_url']);
            }
        }
    }

    public function markAllAsRead()
    {
        /** @var Customer $customer */
        $customer = auth('customer')->user();
        if (!$customer) return;

        $customer->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $this->loadNotifications();
        $this->dispatch('notification-updated');
    }

    public function render()
    {
        return view('livewire.customer.notification-dropdown');
    }
}
