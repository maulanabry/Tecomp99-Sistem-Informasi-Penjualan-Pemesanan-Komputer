<?php

namespace App\Livewire\Owner;

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
    public $statusFilter = '';
    public $orderTypeFilter = '';
    public $methodFilter = '';
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

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingOrderTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingMethodFilter()
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
        // Jika tidak ada pembayaran yang dipilih, tampilkan pesan error dan hentikan proses
        if (!$this->selectedPaymentId) {
            session()->flash('error', 'Pembayaran tidak ditemukan.');
            return;
        }

        try {
            // Ambil data pembayaran berdasarkan payment_id yang dipilih
            $payment = PaymentDetail::where('payment_id', $this->selectedPaymentId)->firstOrFail();

            // Hanya izinkan pembatalan jika status pembayaran bukan 'dibayar', 'gagal', atau 'dibatalkan'
            if (!in_array($payment->status, ['dibayar', 'gagal', 'dibatalkan'])) {

                // Update status pembayaran menjadi 'gagal'
                $payment->status = 'gagal';
                $payment->save();

                // Jika pembayaran terkait order produk, update status pembayaran pada order produk juga
                if ($payment->order_type === 'produk') {
                    $order = OrderProduct::where('order_product_id', $payment->order_product_id)->first();
                    if ($order) {
                        $order->updatePaymentStatus();
                    }
                } else {
                    // Jika pembayaran terkait order servis, update status pembayaran pada order servis juga
                    $order = OrderService::where('order_service_id', $payment->order_service_id)->first();
                    if ($order) {
                        $order->updatePaymentStatus();
                    }
                }

                // Tampilkan pesan sukses jika pembatalan berhasil
                session()->flash('success', 'Pembayaran berhasil dibatalkan.');
            } else {
                // Jika status pembayaran tidak memenuhi syarat, tampilkan pesan error
                session()->flash('error', 'Pembayaran tidak dapat dibatalkan karena sudah diproses atau dibatalkan sebelumnya.');
            }
        } catch (\Exception $e) {
            // Tangkap error jika terjadi kegagalan saat proses pembatalan
            session()->flash('error', 'Gagal membatalkan pembayaran: ' . $e->getMessage());
        }

        // Tutup modal konfirmasi pembatalan
        $this->closeCancelModal();
    }

    public function render()
    {
        return view('livewire.owner.payment-details-table', [
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
                ->when($this->methodFilter, function ($query) {
                    $query->where('method', $this->methodFilter);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage)
        ]);
    }
}
