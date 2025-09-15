<?php

namespace App\Livewire\Teknisi;

use App\Models\OrderService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class OrderServiceCards extends Component
{
    use WithPagination;

    public $search = '';
    public $activeTab = 'all';
    public $statusPaymentFilter = '';
    public $typeFilter = '';
    public $perPage = 12;

    protected $listeners = ['orderServiceSummaryToggled' => 'render'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function updatingStatusPaymentFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->statusPaymentFilter = '';
        $this->typeFilter = '';
        $this->activeTab = 'all';
        $this->resetPage();
    }

    public function getStatusBadgeClass($status)
    {
        return match ($status) {
            'menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100',
            'dijadwalkan' => 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100',
            'menuju_lokasi' => 'bg-orange-100 text-orange-800 dark:bg-orange-800 dark:text-orange-100',
            'diproses' => 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100',
            'menunggu_sparepart' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-800 dark:text-indigo-100',
            'siap_diambil' => 'bg-cyan-100 text-cyan-800 dark:bg-cyan-800 dark:text-cyan-100',
            'diantar' => 'bg-pink-100 text-pink-800 dark:bg-pink-800 dark:text-pink-100',
            'selesai' => 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100',
            'dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
        };
    }

    public function getPaymentBadgeClass($status)
    {
        return match ($status) {
            'belum_dibayar' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            'down_payment' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'lunas' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
        };
    }

    public function getPaymentStatusText($status)
    {
        return match ($status) {
            'belum_dibayar' => 'Belum Dibayar',
            'down_payment' => 'DP',
            'lunas' => 'Lunas',
            default => ucfirst($status)
        };
    }

    public function render()
    {
        $query = OrderService::with(['customer', 'items'])
            ->whereHas('tickets', function ($query) {
                $query->where('admin_id', Auth::id());
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('order_service_id', 'like', '%' . $this->search . '%')
                        ->orWhere('device', 'like', '%' . $this->search . '%')
                        ->orWhere('complaints', 'like', '%' . $this->search . '%')
                        ->orWhereHas('customer', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%')
                                ->orWhere('contact', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->activeTab !== 'all', function ($query) {
                $query->where('status_order', $this->activeTab);
            })
            ->when($this->statusPaymentFilter, function ($query) {
                $query->where('status_payment', $this->statusPaymentFilter);
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->orderBy('created_at', 'desc');

        $orderServices = $query->paginate($this->perPage);

        // Get counts for tabs
        $baseQuery = OrderService::whereHas('tickets', function ($query) {
            $query->where('admin_id', Auth::id());
        });

        $tabCounts = [
            'all' => (clone $baseQuery)->count(),
            'menunggu' => (clone $baseQuery)->where('status_order', 'menunggu')->count(),
            'dijadwalkan' => (clone $baseQuery)->where('status_order', 'dijadwalkan')->count(),
            'menuju_lokasi' => (clone $baseQuery)->where('status_order', 'menuju_lokasi')->count(),
            'diproses' => (clone $baseQuery)->where('status_order', 'diproses')->count(),
            'menunggu_sparepart' => (clone $baseQuery)->where('status_order', 'menunggu_sparepart')->count(),
            'siap_diambil' => (clone $baseQuery)->where('status_order', 'siap_diambil')->count(),
            'diantar' => (clone $baseQuery)->where('status_order', 'diantar')->count(),
            'selesai' => (clone $baseQuery)->where('status_order', 'selesai')->count(),
            'dibatalkan' => (clone $baseQuery)->where('status_order', 'dibatalkan')->count(),
        ];

        return view('livewire.teknisi.order-service-cards', [
            'orderServices' => $orderServices,
            'tabCounts' => $tabCounts
        ]);
    }
}
