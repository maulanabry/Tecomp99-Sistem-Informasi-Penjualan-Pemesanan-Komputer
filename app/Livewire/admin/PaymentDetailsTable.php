<?php

namespace App\Livewire\Admin;

use App\Models\OrderProduct;
use App\Models\OrderService;
use App\Models\PaymentDetail;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentDetailsTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $activeTab = 'all';
    public $orderTypeFilter = '';
    public $perPage = 10;

    public $selectedPaymentId = null;
    public $isCancelModalOpen = false;

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

    public function updatingOrderTypeFilter()
    {
        $this->resetPage();
    }

    public function openCancelModal($paymentId)
    {
        $this->selectedPaymentId = $paymentId;
        $this->isCancelModalOpen = true;
    }

    public function closeCancelModal()
    {
        $this->selectedPaymentId = null;
        $this->isCancelModalOpen = false;
    }

    public function confirmCancelPayment()
    {
        if (!$this->selectedPaymentId) {
            session()->flash('error', 'Pembayaran tidak ditemukan.');
            return;
        }

        try {
            $payment = PaymentDetail::where('payment_id', $this->selectedPaymentId)->firstOrFail();

            if (!in_array($payment->status, ['dibayar', 'gagal'])) {
                // Update payment status to gagal
                $payment->status = 'gagal';
                $payment->save();

                // Update related order's payment status to belum_dibayar
                if ($payment->order_type === 'produk') {
                    $order = OrderProduct::where('order_product_id', $payment->order_product_id)->first();
                    if ($order) {
                        $order->status_payment = 'belum_dibayar';
                        $order->save();
                    }
                } else {
                    $order = OrderService::where('order_service_id', $payment->order_service_id)->first();
                    if ($order) {
                        $order->status_payment = 'belum_dibayar';
                        $order->save();
                    }
                }

                session()->flash('success', 'Pembayaran berhasil dibatalkan.');
            } else {
                session()->flash('error', 'Pembayaran tidak dapat dibatalkan.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membatalkan pembayaran.');
        }

        $this->closeCancelModal();
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
                ->when($this->activeTab !== 'all', function ($query) {
                    $query->where('status', $this->activeTab);
                })
                ->when($this->orderTypeFilter, function ($query) {
                    $query->where('order_type', $this->orderTypeFilter);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage)
        ]);
    }
}
