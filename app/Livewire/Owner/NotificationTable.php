<?php

namespace App\Livewire\Owner;

use App\Enums\NotificationType;
use App\Models\SystemNotification;
use App\Services\NotificationService;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationTable extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $typeFilter = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 20;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    protected $notificationService;

    public function boot(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function mount()
    {
        // Inisialisasi filter dari parameter query
        $this->search = request('search', '');
        $this->statusFilter = request('status', '');
        $this->typeFilter = request('type', '');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function markAsRead($notificationId)
    {
        $owner = auth('pemilik')->user();

        if (!$owner) {
            return;
        }

        $notification = SystemNotification::where('id', $notificationId)
            ->where('notifiable_id', $owner->id)
            ->where('notifiable_type', get_class($owner))
            ->first();

        if ($notification && !$notification->read_at) {
            $notification->update(['read_at' => now()]);
            $this->dispatch('notification-updated');
        }
    }

    public function markAllAsRead()
    {
        $owner = auth('pemilik')->user();

        if (!$owner) {
            return;
        }

        $this->notificationService->markAllAsRead($owner);
        $this->dispatch('notification-updated');
        session()->flash('success', 'Semua notifikasi telah ditandai sebagai dibaca');
    }

    public function deleteNotification($notificationId)
    {
        $owner = auth('pemilik')->user();

        if (!$owner) {
            return;
        }

        $notification = SystemNotification::where('id', $notificationId)
            ->where('notifiable_id', $owner->id)
            ->where('notifiable_type', get_class($owner))
            ->first();

        if ($notification) {
            $notification->delete();
            $this->dispatch('notification-updated');
            session()->flash('success', 'Notifikasi telah dihapus');
        }
    }

    public function navigateToDetail($notificationId)
    {
        $owner = auth('pemilik')->user();

        if (!$owner) {
            return;
        }

        $notification = SystemNotification::where('id', $notificationId)
            ->where('notifiable_id', $owner->id)
            ->where('notifiable_type', get_class($owner))
            ->first();

        if (!$notification) {
            return;
        }

        // Tandai sebagai dibaca ketika diklik
        if (!$notification->read_at) {
            $notification->update(['read_at' => now()]);
            $this->dispatch('notification-updated');
        }

        // Navigasi berdasarkan tipe notifikasi dan data
        $url = $this->getDetailUrl($notification);

        if ($url) {
            return redirect($url);
        }
    }

    private function getDetailUrl($notification)
    {
        $data = $notification->data ?? [];

        switch ($notification->type) {
            case NotificationType::PRODUCT_ORDER_CREATED:
            case NotificationType::PRODUCT_ORDER_PAID:
            case NotificationType::PRODUCT_ORDER_SHIPPED:
                if (isset($data['order_id'])) {
                    return route('pemilik.order-produk.show', $data['order_id']);
                }
                break;

            case NotificationType::SERVICE_ORDER_CREATED:
            case NotificationType::SERVICE_ORDER_PAID:
            case NotificationType::SERVICE_ORDER_STARTED:
            case NotificationType::SERVICE_ORDER_COMPLETED:
                if (isset($data['order_id'])) {
                    return route('pemilik.order-service.show', $data['order_id']);
                }
                break;

            case NotificationType::PAYMENT_RECEIVED:
            case NotificationType::PAYMENT_FAILED:
                if (isset($data['payment_id'])) {
                    return route('owner.payments.show', $data['payment_id']);
                }
                break;

            case NotificationType::SERVICE_TICKET_CREATED:
            case NotificationType::SERVICE_TICKET_UPDATED:
            case NotificationType::SERVICE_TICKET_COMPLETED:
                // Owner mungkin tidak memiliki akses langsung ke service tickets
                // Redirect ke order service terkait jika ada
                if (isset($data['order_id'])) {
                    return route('pemilik.order-service.show', $data['order_id']);
                }
                break;
        }

        return null;
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->typeFilter = '';
        $this->resetPage();
    }

    public function getNotificationsProperty()
    {
        $owner = auth('pemilik')->user();

        if (!$owner) {
            return collect();
        }

        $query = SystemNotification::where('notifiable_id', $owner->id)
            ->where('notifiable_type', get_class($owner));

        // Fungsi pencarian
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('message', 'like', '%' . $this->search . '%')
                    ->orWhere('data->customer_name', 'like', '%' . $this->search . '%')
                    ->orWhere('data->order_id', 'like', '%' . $this->search . '%')
                    ->orWhere('data->device', 'like', '%' . $this->search . '%');
            });
        }

        // Filter status
        if ($this->statusFilter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($this->statusFilter === 'read') {
            $query->whereNotNull('read_at');
        }

        // Filter tipe
        if (!empty($this->typeFilter) && $this->typeFilter !== 'all') {
            $query->where('type', $this->typeFilter);
        }

        // Pengurutan
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate($this->perPage);
    }

    public function getUnreadCountProperty()
    {
        $owner = auth('pemilik')->user();

        if (!$owner) {
            return 0;
        }

        return $this->notificationService->getUnreadCount($owner);
    }

    public function getNotificationTypesProperty()
    {
        return NotificationType::cases();
    }

    public function render()
    {
        return view('livewire.owner.notification-table', [
            'notifications' => $this->notifications,
            'unreadCount' => $this->unreadCount,
            'notificationTypes' => $this->notificationTypes,
        ]);
    }
}
