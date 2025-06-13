<?php

namespace App\Livewire\Admin;

use App\Models\PaymentDetail;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentDetailsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $statusFilter = '';
    public $orderTypeFilter = '';
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

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingOrderTypeFilter()
    {
        $this->resetPage();
    }

    public function deletePayment($paymentId)
    {
        try {
            $payment = PaymentDetail::findOrFail($paymentId);
            $payment->delete();
            session()->flash('success', 'Pembayaran berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus pembayaran.');
        }
    }

    public function render()
    {
        return view('livewire.admin.payment-details-table', [
            'payments' => PaymentDetail::query()
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('payment_id', 'like', '%' . $this->search . '%')
                            ->orWhere('name', 'like', '%' . $this->search . '%')
                            ->orWhere('order_type', 'like', '%' . $this->search . '%');
                    });
                })
                ->when($this->statusFilter, function ($query) {
                    $query->where('status', $this->statusFilter);
                })
                ->when($this->orderTypeFilter, function ($query) {
                    $query->where('order_type', $this->orderTypeFilter);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage)
        ]);
    }
}
