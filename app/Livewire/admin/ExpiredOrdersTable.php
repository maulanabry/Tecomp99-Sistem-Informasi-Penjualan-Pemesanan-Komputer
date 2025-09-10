<?php

namespace App\Livewire\Admin;

use App\Models\OrderProduct;
use App\Models\OrderService;
use Livewire\Component;
use Livewire\WithPagination;

class ExpiredOrdersTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $typeFilter = '';
    public $statusFilter = '';
    public $perPage = 15;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Get melewati_jatuh_tempo OrderProduct records
        $expiredOrderProducts = OrderProduct::where(function ($query) {
            $query->where('status_order', 'melewati_jatuh_tempo')
                ->orWhere(function ($q) {
                    $q->whereNotNull('expired_date')
                        ->where('expired_date', '<', now());
                });
        })
            ->with(['customer', 'items.product'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('order_product_id', 'like', '%' . $this->search . '%')
                        ->orWhereHas('customer', function ($customerQuery) {
                            $customerQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusFilter, function ($query) {
                if ($this->statusFilter === 'melewati_jatuh_tempo') {
                    $query->where('status_order', 'melewati_jatuh_tempo');
                } elseif ($this->statusFilter === 'overdue') {
                    $query->where('status_order', '!=', 'melewati_jatuh_tempo')
                        ->whereNotNull('expired_date')
                        ->where('expired_date', '<', now());
                }
            })
            ->orderBy($this->sortField, $this->sortDirection);

        // Get melewati_jatuh_tempo OrderService records
        $expiredOrderServices = OrderService::where(function ($query) {
            $query->where('status_order', 'melewati_jatuh_tempo')
                ->orWhere(function ($q) {
                    $q->whereNotNull('expired_date')
                        ->where('expired_date', '<', now());
                });
        })
            ->with(['customer', 'items', 'tickets'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('order_service_id', 'like', '%' . $this->search . '%')
                        ->orWhere('device', 'like', '%' . $this->search . '%')
                        ->orWhereHas('customer', function ($customerQuery) {
                            $customerQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusFilter, function ($query) {
                if ($this->statusFilter === 'melewati_jatuh_tempo') {
                    $query->where('status_order', 'melewati_jatuh_tempo');
                } elseif ($this->statusFilter === 'overdue') {
                    $query->where('status_order', '!=', 'melewati_jatuh_tempo')
                        ->whereNotNull('expired_date')
                        ->where('expired_date', '<', now());
                }
            })
            ->orderBy($this->sortField, $this->sortDirection);

        // Combine and filter by type
        $allExpiredOrders = collect();

        if ($this->typeFilter === '' || $this->typeFilter === 'produk') {
            $allExpiredOrders = $allExpiredOrders->merge($expiredOrderProducts->get());
        }

        if ($this->typeFilter === '' || $this->typeFilter === 'servis') {
            $allExpiredOrders = $allExpiredOrders->merge($expiredOrderServices->get());
        }

        // Sort the combined collection
        $allExpiredOrders = $allExpiredOrders->sortBy(function ($order) {
            return $order->{$this->sortField};
        }, SORT_REGULAR, $this->sortDirection === 'desc');

        // Paginate the combined results
        $currentPage = $this->getPage();
        $perPage = $this->perPage;
        $items = $allExpiredOrders->forPage($currentPage, $perPage);

        $paginatedOrders = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $allExpiredOrders->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );

        return view('livewire.admin.expired-orders-table', [
            'expiredOrders' => $paginatedOrders
        ]);
    }
}
