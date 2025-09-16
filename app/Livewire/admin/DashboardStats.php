<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\OrderProduct;
use App\Models\OrderService;
use App\Models\PaymentDetail;
use App\Models\Product;
use App\Models\Service;
use App\Models\ServiceTicket;
use App\Models\Customer;
use Carbon\Carbon;

class DashboardStats extends Component
{
    // Main summary metrics
    public $totalRevenue;
    public $totalRevenueCurrentMonth;
    public $pendingOrders;
    public $ordersInProgress;
    public $lowStockItems;

    // Expandable summary metrics
    public $totalDownPayment;
    public $totalInstallments;
    public $completedServicesNotCollected;
    public $expiredOrdersCount;

    // Left column tabs data
    public $productOrders;
    public $serviceSchedules;
    public $pendingPayments;
    public $expiredOrders;
    public $lowStockProducts;

    // Right column tabs data
    public $revenueFilter = 'monthly';
    public $revenueChart;
    public $paymentStatusChart;
    public $overduePaymentsAnalysis;

    // UI state
    public $showExpandableCards = false;

    public function mount()
    {
        $this->calculateStats();
    }

    public function toggleExpandableCards()
    {
        $this->showExpandableCards = !$this->showExpandableCards;
    }

    public function setRevenueFilter($filter)
    {
        $this->revenueFilter = $filter;
        $this->calculateRevenueChart();
    }



    public function calculateStats()
    {
        // Main summary cards (4 columns)
        $this->calculateMainSummary();

        // Expandable summary cards (4 columns)
        $this->calculateExpandableSummary();

        // Left column tabs data
        $this->calculateOperationalData();

        // Right column tabs data
        $this->calculateAnalyticsData();
    }

    private function calculateMainSummary()
    {
        // 1. Total Pendapatan
        $this->totalRevenue = OrderProduct::where('status_payment', 'lunas')->sum('grand_total') +
            OrderService::where('status_payment', 'lunas')->sum('grand_total');

        // Total Pendapatan Bulan Ini (for header)
        $this->totalRevenueCurrentMonth = OrderProduct::where('status_payment', 'lunas')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->sum('grand_total') +
            OrderService::where('status_payment', 'lunas')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->sum('grand_total');

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

    private function calculateOperationalData()
    {
        // Tab 1: Pesanan Produk (≠ selesai & ≠ dibatalkan, prioritas menunggu & diproses)
        $this->productOrders = OrderProduct::with(['customer', 'items.product'])
            ->whereNotIn('status_order', ['selesai', 'dibatalkan'])
            ->orderByRaw("CASE 
                WHEN status_order = 'menunggu' THEN 1 
                WHEN status_order = 'diproses' THEN 2 
                ELSE 3 END")
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->order_product_id,
                    'customer' => $order->customer->name,
                    'status' => $order->status_order,
                    'payment_status' => $order->status_payment,
                    'amount' => $order->grand_total,
                    'date' => $order->created_at->format('d M Y'),
                    'items_count' => $order->items->count()
                ];
            });

        // Tab 2: Jadwal Servis (semua order servis, urut tanggal terdekat)
        $this->serviceSchedules = OrderService::with(['customer', 'tickets'])
            ->whereHas('tickets')
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get()
            ->map(function ($order) {
                $ticket = $order->tickets->first();
                $visitTime = null;
                $address = null;

                if ($ticket && $ticket->visit_schedule) {
                    $visitTime = Carbon::parse($ticket->visit_schedule)->format('H:i');
                    $address = $order->customer->addresses->first()?->detail_address ?? null;
                }

                return [
                    'id' => $order->order_service_id,
                    'customer' => $order->customer->name,
                    'address' => $address ? (strlen($address) > 30 ? substr($address, 0, 30) . '...' : $address) : '-',
                    'visit_time' => $visitTime ?? '-',
                    'service_type' => $order->type,
                    'status' => $order->status_order,
                    'device' => $order->device
                ];
            });

        // Tab 3: Konfirmasi Pembayaran (status menunggu)
        $this->pendingPayments = PaymentDetail::with(['orderProduct.customer', 'orderService.customer'])
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($payment) {
                $order = $payment->order_type === 'produk' ? $payment->orderProduct : $payment->orderService;
                return [
                    'id' => $payment->payment_id,
                    'customer' => $order->customer->name,
                    'order_id' => $payment->order_type === 'produk' ? $payment->order_product_id : $payment->order_service_id,
                    'order_type' => $payment->order_type,
                    'amount' => $payment->amount,
                    'method' => $payment->method,
                    'payment_type' => $payment->payment_type,
                    'date' => $payment->created_at->format('d M Y H:i')
                ];
            });

        // Tab 4: Pesanan Kedaluwarsa
        $this->expiredOrders = collect()
            ->merge(
                OrderProduct::with('customer')
                    ->whereNotNull('expired_date')
                    ->whereNotIn('status_order', ['selesai', 'dibatalkan'])
                    ->orderBy('expired_date', 'asc')
                    ->take(3)
                    ->get()
                    ->map(function ($order) {
                        $isExpired = Carbon::parse($order->expired_date)->isPast();
                        $daysRemaining = $isExpired ? 0 : Carbon::now()->diffInDays(Carbon::parse($order->expired_date));

                        return [
                            'id' => $order->order_product_id,
                            'customer' => $order->customer->name,
                            'type' => 'Produk',
                            'expired_date' => Carbon::parse($order->expired_date)->format('d M Y'),
                            'is_expired' => $isExpired,
                            'days_remaining' => $daysRemaining,
                            'status_label' => $isExpired ? 'Expired' : "Akan Kedaluwarsa ({$daysRemaining} hari)"
                        ];
                    })
            )
            ->merge(
                OrderService::with('customer')
                    ->whereNotNull('expired_date')
                    ->whereNotIn('status_order', ['selesai', 'dibatalkan'])
                    ->orderBy('expired_date', 'asc')
                    ->take(2)
                    ->get()
                    ->map(function ($order) {
                        $isExpired = Carbon::parse($order->expired_date)->isPast();
                        $daysRemaining = $isExpired ? 0 : Carbon::now()->diffInDays(Carbon::parse($order->expired_date));

                        return [
                            'id' => $order->order_service_id,
                            'customer' => $order->customer->name,
                            'type' => 'Servis',
                            'expired_date' => Carbon::parse($order->expired_date)->format('d M Y'),
                            'is_expired' => $isExpired,
                            'days_remaining' => $daysRemaining,
                            'status_label' => $isExpired ? 'Expired' : "Akan Kedaluwarsa ({$daysRemaining} hari)"
                        ];
                    })
            )
            ->sortBy('expired_date')
            ->take(5);

        // Tab 5: Produk Stok Menipis (stock ≤ 5)
        $this->lowStockProducts = Product::where('stock', '<=', 5)
            ->where('is_active', true)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->product_id,
                    'name' => $product->name,
                    'current_stock' => $product->stock,
                    'safety_stock' => 5,
                    'status' => $product->stock == 0 ? 'Habis' : 'Menipis'
                ];
            });
    }

    private function calculateAnalyticsData()
    {
        // Calculate revenue chart based on current filter
        $this->calculateRevenueChart();

        // Payment status chart
        $this->calculatePaymentStatusChart();

        // Overdue payments analysis
        $this->calculateOverduePaymentsAnalysis();
    }

    private function calculateRevenueChart()
    {
        $labels = [];
        $orderCounts = [];
        $revenues = [];

        switch ($this->revenueFilter) {
            case 'daily':
                // Last 7 days
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('d M');

                    $revenue = OrderProduct::where('status_payment', 'lunas')
                        ->whereDate('updated_at', $date)
                        ->sum('grand_total') +
                        OrderService::where('status_payment', 'lunas')
                        ->whereDate('updated_at', $date)
                        ->sum('grand_total');

                    $count = OrderProduct::where('status_payment', 'lunas')
                        ->whereDate('updated_at', $date)
                        ->count() +
                        OrderService::where('status_payment', 'lunas')
                        ->whereDate('updated_at', $date)
                        ->count();

                    $revenues[] = $revenue;
                    $orderCounts[] = $count;
                }
                break;

            case 'yearly':
                // Last 5 years
                for ($i = 4; $i >= 0; $i--) {
                    $year = Carbon::now()->subYears($i)->year;
                    $labels[] = $year;

                    $revenue = OrderProduct::where('status_payment', 'lunas')
                        ->whereYear('updated_at', $year)
                        ->sum('grand_total') +
                        OrderService::where('status_payment', 'lunas')
                        ->whereYear('updated_at', $year)
                        ->sum('grand_total');

                    $count = OrderProduct::where('status_payment', 'lunas')
                        ->whereYear('updated_at', $year)
                        ->count() +
                        OrderService::where('status_payment', 'lunas')
                        ->whereYear('updated_at', $year)
                        ->count();

                    $revenues[] = $revenue;
                    $orderCounts[] = $count;
                }
                break;

            default: // monthly
                // Last 6 months
                for ($i = 5; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[] = $date->format('M Y');

                    $revenue = OrderProduct::where('status_payment', 'lunas')
                        ->whereYear('updated_at', $date->year)
                        ->whereMonth('updated_at', $date->month)
                        ->sum('grand_total') +
                        OrderService::where('status_payment', 'lunas')
                        ->whereYear('updated_at', $date->year)
                        ->whereMonth('updated_at', $date->month)
                        ->sum('grand_total');

                    $count = OrderProduct::where('status_payment', 'lunas')
                        ->whereYear('updated_at', $date->year)
                        ->whereMonth('updated_at', $date->month)
                        ->count() +
                        OrderService::where('status_payment', 'lunas')
                        ->whereYear('updated_at', $date->year)
                        ->whereMonth('updated_at', $date->month)
                        ->count();

                    $revenues[] = $revenue;
                    $orderCounts[] = $count;
                }
                break;
        }

        $this->revenueChart = [
            'labels' => $labels,
            'revenues' => $revenues,
            'counts' => $orderCounts,
            'total_revenue' => array_sum($revenues),
            'total_orders' => array_sum($orderCounts)
        ];
    }

    private function calculatePaymentStatusChart()
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

        $this->paymentStatusChart = [
            'labels' => ['Menunggu', 'DP/Cicilan', 'Lunas', 'Dibatalkan'],
            'data' => [$menunggu, $dp_cicilan, $lunas, $dibatalkan],
            'colors' => ['#ef4444', '#f59e0b', '#10b981', '#6b7280'],
            'summary' => [
                ['category' => 'Menunggu', 'count' => $menunggu, 'amount' => $menungguAmount],
                ['category' => 'DP/Cicilan', 'count' => $dp_cicilan, 'amount' => $dpCicilanAmount],
                ['category' => 'Lunas', 'count' => $lunas, 'amount' => $lunasAmount],
                ['category' => 'Dibatalkan', 'count' => $dibatalkan, 'amount' => 0]
            ]
        ];
    }

    private function calculateOverduePaymentsAnalysis()
    {
        // Get overdue orders (those with expired_date in the past and not fully paid)
        $overdueProducts = OrderProduct::with('customer')
            ->whereNotNull('expired_date')
            ->where('expired_date', '<', Carbon::now())
            ->whereIn('status_payment', ['belum_dibayar', 'down_payment'])
            ->orderBy('expired_date', 'asc')
            ->take(3)
            ->get();

        $overdueServices = OrderService::with('customer')
            ->whereNotNull('expired_date')
            ->where('expired_date', '<', Carbon::now())
            ->whereIn('status_payment', ['belum_dibayar', 'cicilan'])
            ->orderBy('expired_date', 'asc')
            ->take(2)
            ->get();

        $this->overduePaymentsAnalysis = collect()
            ->merge($overdueProducts->map(function ($order) {
                $daysOverdue = Carbon::parse($order->expired_date)->diffInDays(Carbon::now());
                return [
                    'order_id' => $order->order_product_id,
                    'customer' => $order->customer->name,
                    'due_date' => Carbon::parse($order->expired_date)->format('d M Y'),
                    'days_overdue' => $daysOverdue,
                    'amount' => $order->remaining_balance,
                    'type' => 'Produk'
                ];
            }))
            ->merge($overdueServices->map(function ($order) {
                $daysOverdue = Carbon::parse($order->expired_date)->diffInDays(Carbon::now());
                return [
                    'order_id' => $order->order_service_id,
                    'customer' => $order->customer->name,
                    'due_date' => Carbon::parse($order->expired_date)->format('d M Y'),
                    'days_overdue' => $daysOverdue,
                    'amount' => $order->remaining_balance,
                    'type' => 'Servis'
                ];
            }))
            ->sortByDesc('days_overdue')
            ->take(5);
    }

    public function refreshDashboard()
    {
        $this->calculateStats();
    }

    private function getMonthlyRevenueData()
    {
        $months = [];
        $revenues = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');

            $revenue = OrderProduct::where('status_payment', 'lunas')
                ->whereYear('updated_at', $date->year)
                ->whereMonth('updated_at', $date->month)
                ->sum('grand_total') +
                OrderService::where('status_payment', 'lunas')
                ->whereYear('updated_at', $date->year)
                ->whereMonth('updated_at', $date->month)
                ->sum('grand_total');

            $revenues[] = $revenue;
        }

        return [
            'labels' => $months,
            'data' => $revenues
        ];
    }

    private function getOrderStatusData()
    {
        $menunggu = OrderProduct::where('status_order', 'menunggu')->count() +
            OrderService::where('status_order', 'menunggu')->count();
        $inden = OrderProduct::where('status_order', 'inden')->count();
        $siap_kirim = OrderProduct::where('status_order', 'siap_kirim')->count();
        $diproses = OrderProduct::where('status_order', 'diproses')->count() +
            OrderService::where('status_order', 'diproses')->count();
        $dikirim = OrderProduct::where('status_order', 'dikirim')->count() +
            OrderService::where('status_order', 'dikirim')->count();
        $selesai = OrderProduct::where('status_order', 'selesai')->count() +
            OrderService::where('status_order', 'selesai')->count();
        $dibatalkan = OrderProduct::where('status_order', 'dibatalkan')->count() +
            OrderService::where('status_order', 'dibatalkan')->count();

        return [
            'labels' => ['Menunggu', 'Inden', 'Siap Kirim', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'],
            'data' => [$menunggu, $inden, $siap_kirim, $diproses, $dikirim, $selesai, $dibatalkan],
            'colors' => ['#f59e0b', '#ea580c', '#9333ea', '#3b82f6', '#8b5cf6', '#10b981', '#ef4444']
        ];
    }

    private function getServiceStatusData()
    {
        $menunggu = ServiceTicket::where('status', 'menunggu')->count();
        $dijadwalkan = ServiceTicket::where('status', 'dijadwalkan')->count();
        $menuju_lokasi = ServiceTicket::where('status', 'menuju_lokasi')->count();
        $diproses = ServiceTicket::where('status', 'diproses')->count();
        $menunggu_sparepart = ServiceTicket::where('status', 'menunggu_sparepart')->count();
        $siap_diambil = ServiceTicket::where('status', 'siap_diambil')->count();
        $diantar = ServiceTicket::where('status', 'diantar')->count();
        $selesai = ServiceTicket::where('status', 'selesai')->count();
        $dibatalkan = ServiceTicket::where('status', 'dibatalkan')->count();
        $melewati_jatuh_tempo = ServiceTicket::where('status', 'melewati_jatuh_tempo')->count();

        return [
            'labels' => ['Menunggu', 'Dijadwalkan', 'Menuju Lokasi', 'Diproses', 'Menunggu Sparepart', 'Siap Diambil', 'Diantar', 'Selesai', 'Dibatalkan', 'Melewati_jatuh_tempo'],
            'data' => [$menunggu, $dijadwalkan, $menuju_lokasi, $diproses, $menunggu_sparepart, $siap_diambil, $diantar, $selesai, $dibatalkan, $melewati_jatuh_tempo],
            'colors' => ['#f59e0b', '#fbbf24', '#f97316', '#3b82f6', '#8b5cf6', '#a855f7', '#ec4899', '#10b981', '#ef4444', '#6b7280']
        ];
    }

    private function getInventoryData()
    {
        $inStock = Product::where('stock', '>', 5)->where('is_active', true)->count();
        $lowStock = Product::where('stock', '>', 0)->where('stock', '<=', 5)->where('is_active', true)->count();
        $outOfStock = Product::where('stock', '=', 0)->where('is_active', true)->count();

        return [
            'labels' => ['Stok Aman', 'Stok Menipis', 'Habis Stok'],
            'data' => [$inStock, $lowStock, $outOfStock],
            'colors' => ['#10b981', '#f59e0b', '#ef4444']
        ];
    }

    private function getExpiredOrders()
    {
        $expiredProducts = OrderProduct::where('is_expired', true)->count();
        $expiredServices = OrderService::where('is_expired', true)->count();

        return [
            'total' => $expiredProducts + $expiredServices,
            'products' => $expiredProducts,
            'services' => $expiredServices
        ];
    }

    private function getOverdueServices()
    {
        $overdueCount = OrderService::whereNotNull('estimated_completion')
            ->where('estimated_completion', '<', now())
            ->whereNotIn('status_order', ['selesai', 'dibatalkan'])
            ->count();

        return [
            'total' => $overdueCount
        ];
    }

    private function getPaymentStatusData()
    {
        $belumDibayar = OrderProduct::where('status_payment', 'belum_dibayar')->count() +
            OrderService::where('status_payment', 'belum_dibayar')->count();

        $downPayment = OrderProduct::where('status_payment', 'down_payment')->count() +
            OrderService::where('status_payment', 'cicilan')->count();

        $lunas = OrderProduct::where('status_payment', 'lunas')->count() +
            OrderService::where('status_payment', 'lunas')->count();

        $dibatalkan = OrderProduct::where('status_payment', 'dibatalkan')->count() +
            OrderService::where('status_payment', 'dibatalkan')->count();

        return [
            'labels' => ['Belum Dibayar', 'Down Payment/Cicilan', 'Lunas', 'Dibatalkan'],
            'data' => [$belumDibayar, $downPayment, $lunas, $dibatalkan],
            'colors' => ['#ef4444', '#f59e0b', '#10b981', '#6b7280']
        ];
    }



    public function render()
    {
        return view('livewire.admin.dashboard-stats');
    }
}
