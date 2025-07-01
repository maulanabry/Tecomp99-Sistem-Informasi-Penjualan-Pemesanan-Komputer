<?php

namespace App\Livewire\Teknisi;

use App\Models\OrderService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class OrderServiceTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $activeTab = 'all';
    public $statusPaymentFilter = '';
    public $typeFilter = '';
    public $perPage = 10;

    protected $listeners = ['orderServiceSummaryToggled' => 'render'];

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

    public function render()
    {
        return view('livewire.teknisi.order-service-table', [
            'orderServices' => OrderService::with('customer')
                ->whereHas('tickets', function ($query) {
                    $query->where('admin_id', Auth::id());
                })
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('order_service_id', 'like', '%' . $this->search . '%')
                            ->orWhere('device', 'like', '%' . $this->search . '%')
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
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage)
        ]);
    }
}
