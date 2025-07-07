<?php

namespace App\Livewire\Teknisi;

use App\Enums\NotificationType;
use App\Models\SystemNotification;
use App\Services\NotificationService;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationTable extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';
    public $statusFilter = 'all'; // all, read, unread

    protected $notificationService;

    public function boot(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function markAsRead($notificationId)
    {
        $teknisi = auth('teknisi')->user();

        $notification = SystemNotification::where('id', $notificationId)
            ->where('notifiable_id', $teknisi->id)
            ->where('notifiable_type', get_class($teknisi))
            ->first();

        if ($notification && !$notification->read_at) {
            $notification->update(['read_at' => now()]);
            $this->dispatch('notification-updated');
        }
    }

    public function markAllAsRead()
    {
        $teknisi = auth('teknisi')->user();
        $this->notificationService->markAllAsRead($teknisi);
        $this->dispatch('notification-updated');
        session()->flash('success', 'Semua notifikasi telah ditandai sebagai dibaca');
    }

    public function deleteNotification($notificationId)
    {
        $teknisi = auth('teknisi')->user();

        $notification = SystemNotification::where('id', $notificationId)
            ->where('notifiable_id', $teknisi->id)
            ->where('notifiable_type', get_class($teknisi))
            ->first();

        if ($notification) {
            $notification->delete();
            $this->dispatch('notification-updated');
            session()->flash('success', 'Notifikasi telah dihapus');
        }
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->typeFilter = '';
        $this->statusFilter = 'all';
    }

    public function navigateToDetail($notificationId)
    {
        $teknisi = auth('teknisi')->user();

        $notification = SystemNotification::where('id', $notificationId)
            ->where('notifiable_id', $teknisi->id)
            ->where('notifiable_type', get_class($teknisi))
            ->first();

        if (!$notification) {
            return;
        }

        // Mark as read when clicked
        if (!$notification->read_at) {
            $notification->update(['read_at' => now()]);
            $this->dispatch('notification-updated');
        }

        // Navigate based on notification type and data
        $url = $this->getDetailUrl($notification);

        if ($url) {
            return redirect($url);
        }
    }

    private function getDetailUrl($notification)
    {
        $data = $notification->data ?? [];

        switch ($notification->type) {
            case NotificationType::TEKNISI_ASSIGNED_TICKET:
            case NotificationType::SERVICE_TICKET_CREATED:
            case NotificationType::SERVICE_TICKET_UPDATED:
            case NotificationType::SERVICE_TICKET_COMPLETED:
                if (isset($data['ticket_id'])) {
                    return route('teknisi.service-tickets.show', $data['ticket_id']);
                }
                return route('teknisi.service-tickets.index');

            case NotificationType::TEKNISI_ORDER_UPDATED:
            case NotificationType::SERVICE_ORDER_CREATED:
            case NotificationType::SERVICE_ORDER_PAID:
            case NotificationType::SERVICE_ORDER_STARTED:
            case NotificationType::SERVICE_ORDER_COMPLETED:
                if (isset($data['order_id'])) {
                    return route('teknisi.order-services.show', $data['order_id']);
                }
                return route('teknisi.order-services.index');

            case NotificationType::TEKNISI_VISIT_TODAY:
            case NotificationType::TEKNISI_VISIT_OVERDUE:
                return route('teknisi.jadwal-servis.index');

            default:
                return route('teknisi.dashboard.index');
        }
    }

    public function getNotificationsProperty()
    {
        $teknisi = auth('teknisi')->user();

        $query = SystemNotification::where('notifiable_id', $teknisi->id)
            ->where('notifiable_type', \App\Models\Admin::class);

        // Apply search filter
        if ($this->search) {
            $query->where('message', 'like', '%' . $this->search . '%');
        }

        // Apply type filter
        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        // Apply status filter
        if ($this->statusFilter === 'read') {
            $query->whereNotNull('read_at');
        } elseif ($this->statusFilter === 'unread') {
            $query->whereNull('read_at');
        }

        return $query->latest()->paginate(15);
    }

    public function getUnreadCountProperty()
    {
        $teknisi = auth('teknisi')->user();
        return $this->notificationService->getUnreadCount($teknisi);
    }

    public function getNotificationTypesProperty()
    {
        return NotificationType::cases();
    }

    public function render()
    {
        return view('livewire.teknisi.notification-table', [
            'notifications' => $this->notifications,
            'unreadCount' => $this->unreadCount,
            'notificationTypes' => $this->notificationTypes,
        ]);
    }
}
