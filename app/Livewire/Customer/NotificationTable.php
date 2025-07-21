<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\SystemNotification;
use App\Models\Customer;
use App\Enums\NotificationType;

class NotificationTable extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';
    public $readFilter = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'readFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingReadFilter()
    {
        $this->resetPage();
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

        session()->flash('success', 'Semua notifikasi telah ditandai sebagai dibaca');
    }

    public function deleteNotification($notificationId)
    {
        /** @var Customer $customer */
        $customer = auth('customer')->user();
        if (!$customer) return;

        $notification = $customer->notifications()
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            $notification->delete();
            session()->flash('success', 'Notifikasi berhasil dihapus');
        }
    }

    public function navigateToDetail($notificationId)
    {
        /** @var Customer $customer */
        $customer = auth('customer')->user();
        if (!$customer) return;

        $notification = $customer->notifications()
            ->where('id', $notificationId)
            ->first();

        if ($notification) {
            // Mark as read
            if (!$notification->read_at) {
                $notification->markAsRead();
            }

            // Navigate to action URL if available
            if (isset($notification->data['action_url'])) {
                return redirect($notification->data['action_url']);
            }
        }
    }

    public function getNotificationsProperty()
    {
        /** @var Customer $customer */
        $customer = auth('customer')->user();
        if (!$customer) return collect();

        $query = $customer->notifications()
            ->with('subject')
            ->latest();

        // Apply search filter
        if ($this->search) {
            $query->where('message', 'like', '%' . $this->search . '%');
        }

        // Apply type filter
        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        // Apply read status filter
        if ($this->readFilter === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($this->readFilter === 'unread') {
            $query->whereNull('read_at');
        }

        return $query->paginate(10);
    }

    public function getNotificationTypesProperty()
    {
        // Return only customer notification types
        return collect(NotificationType::cases())
            ->filter(fn($type) => str_starts_with($type->value, 'customer.'));
    }

    public function render()
    {
        return view('livewire.customer.notification-table', [
            'notifications' => $this->notifications,
            'notificationTypes' => $this->notificationTypes,
        ]);
    }
}
