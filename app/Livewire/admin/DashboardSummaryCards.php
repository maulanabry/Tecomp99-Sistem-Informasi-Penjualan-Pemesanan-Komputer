<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\OrderProduct;
use App\Models\OrderService;
use App\Models\PaymentDetail;
use App\Models\Product;
use Carbon\Carbon;

class DashboardSummaryCards extends Component
{
    // Main summary cards (4 columns)
    public $totalRevenue;
    public $pendingOrders;
    public $ordersInProgress;
    public $lowStockItems;

    // Expandable cards (4 columns)
    public $totalDownPayment;
    public $totalInstallments;
    public $completedServicesNotCollected;
    public $expiredOrdersCount;

    // UI state
    public $showExpandableCards = false;

    protected $listeners = ['refresh-dashboard' => 'calculateStats'];

    public function mount()
    {
        $this->calculateStats();
    }

    public function toggleExpandableCards()
    {
        $this->showExpandableCards = !$this->showExpandableCards;
    }

    public function calculateStats()
    {
        $this->calculateMainSummary();
        $this->calculateExpandableSummary();
    }

    private function calculateMainSummary()
    {
        // 1. Total Pendapatan
        $this->totalRevenue = OrderProduct::where('status_payment', 'lunas')->sum('grand_total') +
            OrderService::where('status_payment', 'lunas')->sum('grand_total');

        // 2. Pesanan Menunggu
        $this->pendingOrders = OrderProduct::where('status_order', 'menunggu')->count() +
            OrderService::where('status_order', 'menunggu')->count();

        // 3. Pesanan Diproses
        $this->ordersInProgress = OrderProduct::where('status_order', 'diproses')->count() +
            OrderService::where('status_order', 'diproses')->count();

        // 4. Stok Menipis
        $this->lowStockItems = Product::where('stock', '<=', 5)
            ->where('is_active', true)
            ->count();
    }

    private function calculateExpandableSummary()
    {
        // 1. Total Down Payment
        $this->totalDownPayment = [
            'count' => PaymentDetail::where('payment_type', 'down_payment')
                ->where('status', 'dibayar')
                ->count(),
            'amount' => PaymentDetail::where('payment_type', 'down_payment')
                ->where('status', 'dibayar')
                ->sum('amount')
        ];

        // 2. Total Cicilan
        $this->totalInstallments = [
            'count' => PaymentDetail::where('payment_type', 'cicilan')
                ->where('status', 'dibayar')
                ->count(),
            'amount' => PaymentDetail::where('payment_type', 'cicilan')
                ->where('status', 'dibayar')
                ->sum('amount')
        ];

        // 3. Servis Selesai Belum Diambil
        $this->completedServicesNotCollected = OrderService::where('status_order', 'selesai')
            ->where('status_payment', '!=', 'lunas')
            ->count();

        // 4. Pesanan Melewati Batas Waktu
        $this->expiredOrdersCount = OrderProduct::whereNotNull('expired_date')
            ->where('expired_date', '<', Carbon::now())
            ->whereNotIn('status_order', ['selesai', 'dibatalkan'])
            ->count() +
            OrderService::whereNotNull('expired_date')
            ->where('expired_date', '<', Carbon::now())
            ->whereNotIn('status_order', ['selesai', 'dibatalkan'])
            ->count();
    }

    public function render()
    {
        return view('livewire.admin.dashboard-summary-cards');
    }
}
