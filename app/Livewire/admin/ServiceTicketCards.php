<?php

namespace App\Livewire\Admin;

use App\Models\ServiceTicket;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceTicketCards extends Component
{
    use WithPagination;

    public $search = '';
    public $activeTab = 'all';
    public $serviceTypeFilter = '';
    public $statusFilter = '';
    public $perPage = 12;

    public $tabCounts = [
        'all' => 0,
        'Menunggu' => 0,
        'Diproses' => 0,
        'Diantar' => 0,
        'Perlu Diambil' => 0,
        'Selesai' => 0,
        'Dibatalkan' => 0,
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

    public function updatingStatusFilter()
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
        $this->statusFilter = '';
        $this->activeTab = 'all';
        $this->resetPage();
        $this->calculateTabCounts();
    }

    public function calculateTabCounts()
    {
        $baseQuery = ServiceTicket::with(['orderService.customer'])
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
        $this->tabCounts['Menunggu'] = (clone $baseQuery)->where('status', 'Menunggu')->count();
        $this->tabCounts['Diproses'] = (clone $baseQuery)->where('status', 'Diproses')->count();
        $this->tabCounts['Diantar'] = (clone $baseQuery)->where('status', 'Diantar')->count();
        $this->tabCounts['Perlu Diambil'] = (clone $baseQuery)->where('status', 'Perlu Diambil')->count();
        $this->tabCounts['Selesai'] = (clone $baseQuery)->where('status', 'Selesai')->count();
        $this->tabCounts['Dibatalkan'] = (clone $baseQuery)->where('status', 'Dibatalkan')->count();
    }

    public function getStatusBadgeClass($status)
    {
        return match ($status) {
            'Menunggu' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            'Diproses' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            'Diantar' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
            'Perlu Diambil' => 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
            'Selesai' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
            'Dibatalkan' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
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

        return view('livewire.admin.service-ticket-cards', [
            'serviceTickets' => $serviceTickets
        ]);
    }
}
