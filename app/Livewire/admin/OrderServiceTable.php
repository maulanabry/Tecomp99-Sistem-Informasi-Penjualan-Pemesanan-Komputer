<?php

namespace App\Livewire\Admin;

use App\Models\OrderService;
use Livewire\Component;
use Livewire\WithPagination;

class OrderServiceTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $statusOrderFilter = '';
    public $statusPaymentFilter = '';
    public $typeFilter = '';
    public $perPage = 10;

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

    public function updatingStatusOrderFilter()
    {
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

    public function cancelOrder($orderServiceId)
    {
        try {
            $orderService = OrderService::findOrFail($orderServiceId);

            if ($orderService->status_order !== 'Selesai' && $orderService->status_payment !== 'lunas') {
                $orderService->update([
                    'status_order' => 'Dibatalkan',
                    'status_payment' => 'dibatalkan'
                ]);

                session()->flash('success', 'Order servis berhasil dibatalkan.');
            } else {
                session()->flash('error', 'Order servis tidak dapat dibatalkan.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membatalkan order servis.');
        }
    }

    public function render()
    {
        return view('livewire.admin.order-service-table', [
            'orderServices' => OrderService::with('customer')
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
                ->when($this->statusOrderFilter, function ($query) {
                    $query->where('status_order', $this->statusOrderFilter);
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
