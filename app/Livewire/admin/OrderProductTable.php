<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\OrderProduct;
use App\Models\Customer;

class OrderProductTable extends Component
{
    use WithPagination;

    public $search = '';
    public $orderStatusFilter = '';
    public $paymentStatusFilter = '';
    public $typeFilter = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $listeners = ['refreshOrderProductTable' => '$refresh'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingOrderStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPaymentStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function render()
    {
        $query = OrderProduct::query()->with('customer');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_product_id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($q2) {
                        $q2->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->orderStatusFilter) {
            $query->where('status_order', $this->orderStatusFilter);
        }

        if ($this->paymentStatusFilter) {
            $query->where('status_payment', $this->paymentStatusFilter);
        }

        if ($this->typeFilter) {
            $query->where('type', $this->typeFilter);
        }

        $orderProducts = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.order-product-table', [
            'orderProducts' => $orderProducts,
        ]);
    }
}
