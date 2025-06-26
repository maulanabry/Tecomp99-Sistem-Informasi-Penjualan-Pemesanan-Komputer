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
    public $activeTab = 'all';
    public $statusPaymentFilter = '';
    public $typeFilter = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $selectedOrderProductId = null;
    public $isCancelModalOpen = false;

    protected $listeners = ['refreshOrderProductTable' => '$refresh'];

    public function openCancelModal($orderProductId)
    {
        $this->selectedOrderProductId = $orderProductId;
        $this->isCancelModalOpen = true;
    }

    public function closeCancelModal()
    {
        $this->selectedOrderProductId = null;
        $this->isCancelModalOpen = false;
    }

    public function confirmCancelOrder()
    {
        if (!$this->selectedOrderProductId) {
            session()->flash('error', 'Order produk tidak ditemukan.');
            return;
        }

        try {
            $orderProduct = OrderProduct::findOrFail($this->selectedOrderProductId);

            if ($orderProduct->status_order !== 'selesai' && $orderProduct->status_payment !== 'lunas') {
                $orderProduct->update([
                    'status_order' => 'dibatalkan',
                    'status_payment' => 'dibatalkan'
                ]);

                session()->flash('success', 'Order produk berhasil dibatalkan.');
            } else {
                session()->flash('error', 'Order produk tidak dapat dibatalkan.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membatalkan order produk.');
        }

        $this->closeCancelModal();
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

        // Filter by active tab instead of statusOrderFilter
        if ($this->activeTab !== 'all') {
            $query->where('status_order', $this->activeTab);
        }

        if ($this->statusPaymentFilter) {
            $query->where('status_payment', $this->statusPaymentFilter);
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
