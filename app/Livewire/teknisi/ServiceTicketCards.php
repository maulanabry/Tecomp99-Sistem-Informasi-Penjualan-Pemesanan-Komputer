<?php

namespace App\Livewire\Teknisi;

use App\Models\ServiceTicket;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ServiceTicketCards extends Component
{
    use WithPagination;

    public $search = '';
    public $activeTab = 'all';
    public $serviceTypeFilter = '';
    public $perPage = 12;

    public $tabCounts = [
        'all' => 0,
        'menunggu' => 0,
        'dijadwalkan' => 0,
        'menuju_lokasi' => 0,
        'diproses' => 0,
        'menunggu_sparepart' => 0,
        'siap_diambil' => 0,
        'diantar' => 0,
        'selesai' => 0,
        'dibatalkan' => 0,
    ];

    protected $listeners = ['refreshServiceTicketCards' => '$refresh'];

    public function mount()
    {
        $this->calculateTabCounts();
    }

    public function updatingSearch()
    {
        $this->resetPage();
        $this->calculateTabCounts();
    }

    public function updatingServiceTypeFilter()
    {
        $this->resetPage();
        $this->calculateTabCounts();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->serviceTypeFilter = '';
        $this->activeTab = 'all';
        $this->resetPage();
        $this->calculateTabCounts();
    }

    public function calculateTabCounts()
    {
        $baseQuery = ServiceTicket::with(['orderService.customer'])
            ->where('admin_id', Auth::guard('teknisi')->id()) // Only show tickets assigned to current teknisi
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('service_ticket_id', 'like', '%' . $this->search . '%')
                        ->orWhere('order_service_id', 'like', '%' . $this->search . '%')
                        ->orWhereHas('orderService.customer', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('orderService', function ($q) {
                            $q->where('device', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->serviceTypeFilter, function ($query) {
                $query->whereHas('orderService', function ($q) {
                    $q->where('type', $this->serviceTypeFilter);
                });
            });

        $this->tabCounts['all'] = (clone $baseQuery)->count();
        $this->tabCounts['menunggu'] = (clone $baseQuery)->where('status', 'menunggu')->count();
        $this->tabCounts['dijadwalkan'] = (clone $baseQuery)->where('status', 'dijadwalkan')->count();
        $this->tabCounts['menuju_lokasi'] = (clone $baseQuery)->where('status', 'menuju_lokasi')->count();
        $this->tabCounts['diproses'] = (clone $baseQuery)->where('status', 'diproses')->count();
        $this->tabCounts['menunggu_sparepart'] = (clone $baseQuery)->where('status', 'menunggu_sparepart')->count();
        $this->tabCounts['siap_diambil'] = (clone $baseQuery)->where('status', 'siap_diambil')->count();
        $this->tabCounts['diantar'] = (clone $baseQuery)->where('status', 'diantar')->count();
        $this->tabCounts['selesai'] = (clone $baseQuery)->where('status', 'selesai')->count();
        $this->tabCounts['dibatalkan'] = (clone $baseQuery)->where('status', 'dibatalkan')->count();
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
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
        };
    }

    public function getServiceTypeBadgeClass($type)
    {
        return match ($type) {
            'reguler' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'onsite' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
        };
    }

    public function render()
    {
        $serviceTickets = ServiceTicket::query()
            ->with(['orderService.customer', 'admin', 'actions'])
            ->where('admin_id', Auth::guard('teknisi')->id()) // Only show tickets assigned to current teknisi
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('service_ticket_id', 'like', '%' . $this->search . '%')
                        ->orWhere('order_service_id', 'like', '%' . $this->search . '%')
                        ->orWhereHas('orderService.customer', function ($q) {
                            $q->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('orderService', function ($q) {
                            $q->where('device', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->activeTab !== 'all', function ($query) {
                $query->where('status', $this->activeTab);
            })
            ->when($this->serviceTypeFilter, function ($query) {
                $query->whereHas('orderService', function ($q) {
                    $q->where('type', $this->serviceTypeFilter);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.teknisi.service-ticket-cards', [
            'serviceTickets' => $serviceTickets
        ]);
    }
}
