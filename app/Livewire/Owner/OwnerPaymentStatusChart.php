<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\PaymentDetail;

class OwnerPaymentStatusChart extends Component
{
    protected $listeners = ['refresh-dashboard' => '$refresh', 'refresh-charts' => 'loadPaymentStatusData'];

    public $paymentStatusData;

    public function mount()
    {
        $this->loadPaymentStatusData();
    }

    public function loadPaymentStatusData()
    {
        $this->paymentStatusData = $this->getPaymentStatusData();
    }

    private function getPaymentStatusData()
    {
        $menunggu = PaymentDetail::where('status', 'menunggu')->count();
        $dp = PaymentDetail::where('payment_type', 'down_payment')->where('status', 'dibayar')->count();
        $cicilan = PaymentDetail::where('payment_type', 'cicilan')->where('status', 'dibayar')->count();
        $lunas = PaymentDetail::where('status', 'dibayar')->where('payment_type', 'full_payment')->count();

        $total = $menunggu + $dp + $cicilan + $lunas;

        return [
            'labels' => ['Menunggu', 'DP', 'Cicilan', 'Lunas'],
            'data' => [$menunggu, $dp, $cicilan, $lunas],
            'total' => $total,
            'menunggu_percentage' => $total > 0 ? round(($menunggu / $total) * 100, 1) : 0,
            'dp_percentage' => $total > 0 ? round(($dp / $total) * 100, 1) : 0,
            'cicilan_percentage' => $total > 0 ? round(($cicilan / $total) * 100, 1) : 0,
            'lunas_percentage' => $total > 0 ? round(($lunas / $total) * 100, 1) : 0
        ];
    }

    public function render()
    {
        return view('livewire.owner.owner-payment-status-chart');
    }
}
