<?php

namespace App\Livewire\Owner;

use App\Models\PaymentDetail;
use Livewire\Component;

class PaymentSummaryCard extends Component
{
    public $showAllCards = false;

    public function toggleCards()
    {
        $this->showAllCards = !$this->showAllCards;
    }

    public function getSummaries()
    {
        return [
            'total_pembayaran' => PaymentDetail::count(),
            'total_pendapatan' => PaymentDetail::where('status', 'dibayar')->sum('amount'),
            'pembayaran_produk' => PaymentDetail::where('order_type', 'produk')->count(),
            'pembayaran_servis' => PaymentDetail::where('order_type', 'servis')->count(),
            'metode_tunai' => PaymentDetail::where('method', 'Tunai')->count(),
            'metode_bank_bca' => PaymentDetail::where('method', 'Bank BCA')->count(),
            'pembayaran_gagal' => PaymentDetail::where('status', 'gagal')->count(),
            'pembayaran_menunggu' => PaymentDetail::where('status', 'pending')->count(),
        ];
    }

    public function render()
    {
        return view('livewire.owner.payment-summary-card', [
            'summaries' => $this->getSummaries()
        ]);
    }
}
