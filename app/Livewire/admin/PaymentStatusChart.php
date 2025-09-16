<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\OrderProduct;
use App\Models\OrderService;
use App\Models\PaymentDetail;
use Carbon\Carbon;

class PaymentStatusChart extends Component
{
    public $chartData = [];
    public $summaryData = [];
    public $paymentMetrics = [];

    protected $listeners = ['refresh-dashboard' => 'loadData'];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $menunggu = OrderProduct::where('status_payment', 'belum_dibayar')->count() +
            OrderService::where('status_payment', 'belum_dibayar')->count();

        $dp_cicilan = OrderProduct::where('status_payment', 'down_payment')->count() +
            OrderService::where('status_payment', 'cicilan')->count();

        $lunas = OrderProduct::where('status_payment', 'lunas')->count() +
            OrderService::where('status_payment', 'lunas')->count();

        $dibatalkan = OrderProduct::where('status_payment', 'dibatalkan')->count() +
            OrderService::where('status_payment', 'dibatalkan')->count();

        // Calculate amounts
        $menungguAmount = OrderProduct::where('status_payment', 'belum_dibayar')->sum('grand_total') +
            OrderService::where('status_payment', 'belum_dibayar')->sum('grand_total');

        $dpCicilanAmount = OrderProduct::where('status_payment', 'down_payment')->sum('remaining_balance') +
            OrderService::where('status_payment', 'cicilan')->sum('remaining_balance');

        $lunasAmount = OrderProduct::where('status_payment', 'lunas')->sum('grand_total') +
            OrderService::where('status_payment', 'lunas')->sum('grand_total');

        $this->chartData = [
            'labels' => ['Menunggu', 'DP/Cicilan', 'Lunas', 'Dibatalkan'],
            'data' => [$menunggu, $dp_cicilan, $lunas, $dibatalkan],
            'colors' => ['#ef4444', '#f59e0b', '#10b981', '#6b7280']
        ];

        $this->summaryData = [
            ['total_amount' => $menungguAmount],
            ['total_amount' => $dpCicilanAmount],
            ['total_amount' => $lunasAmount],
            ['total_amount' => 0]
        ];

        $this->calculatePaymentMetrics();
    }

    private function calculatePaymentMetrics()
    {
        $totalOrders = array_sum($this->chartData['data']);
        $completedOrders = $this->chartData['data'][2]; // Lunas

        $this->paymentMetrics = [
            'completion_rate' => $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0,
            'avg_payment_days' => $this->getAveragePaymentDays(),
            'monthly_payments' => $this->getMonthlyPayments(),
            'pending_amount' => $this->summaryData[0]['total_amount'] + $this->summaryData[1]['total_amount']
        ];
    }

    private function getAveragePaymentDays()
    {
        $avgDays = PaymentDetail::where('status', 'dibayar')
            ->whereMonth('created_at', Carbon::now()->month)
            ->selectRaw('AVG(DATEDIFF(updated_at, created_at)) as avg_days')
            ->value('avg_days');

        return round($avgDays ?? 0, 1);
    }

    private function getMonthlyPayments()
    {
        return PaymentDetail::where('status', 'dibayar')
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
    }

    public function showPendingPayments()
    {
        return redirect()->route('admin.payment.index', ['status' => 'menunggu']);
    }

    public function showOverduePayments()
    {
        return redirect()->route('admin.orders.expired');
    }

    public function render()
    {
        return view('livewire.admin.payment-status-chart');
    }
}
