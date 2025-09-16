<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\PaymentDetail;

class PendingPaymentsList extends Component
{
    public $pendingPayments = [];

    protected $listeners = ['refresh-dashboard' => 'loadData'];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->pendingPayments = PaymentDetail::with(['orderProduct.customer', 'orderService.customer'])
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    public function approvePayment($paymentId)
    {
        $payment = PaymentDetail::find($paymentId);
        if ($payment) {
            $payment->update(['status' => 'dibayar']);
            session()->flash('payment_message', 'Pembayaran berhasil disetujui');
            session()->flash('payment_type', 'success');
            $this->loadData();
        }
    }

    public function rejectPayment($paymentId)
    {
        $payment = PaymentDetail::find($paymentId);
        if ($payment) {
            $payment->update(['status' => 'gagal']);
            session()->flash('payment_message', 'Pembayaran ditolak');
            session()->flash('payment_type', 'error');
            $this->loadData();
        }
    }

    public function render()
    {
        return view('livewire.admin.pending-payments-list');
    }
}
