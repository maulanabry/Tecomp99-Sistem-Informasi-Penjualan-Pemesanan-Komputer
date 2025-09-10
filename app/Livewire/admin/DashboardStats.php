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
    public $totalRevenue;
    public $pendingOrders;
    public $activeTickets;
    public $lowStockItems;
    public $recentOrders;
    public $serviceTickets;
    public $monthlyRevenue;
    public $topProducts;
    public $topServices;
    public $monthlyRevenueChart;
    public $orderStatusChart;
    public $serviceStatusChart;
    public $inventoryChart;
    public $expiredOrders;
    public $overdueServices;
    public $paymentStatusChart;
    public $servicePerformanceMetrics;

    public function mount()
    {
        $this->calculateStats();
    }

    public function calculateStats()
    {
        // Menghitung total pendapatan
        $this->totalRevenue = PaymentDetail::where('status', 'dibayar')
            ->sum('amount');

        // Menghitung pesanan yang menunggu
        $this->pendingOrders = OrderProduct::where('status_order', 'menunggu')
            ->count() +
            OrderService::where('status_order', 'Menunggu')
            ->count();

        // Menghitung tiket servis yang aktif
        $this->activeTickets = ServiceTicket::whereNotIn('status', ['Selesai', 'Dibatalkan'])
            ->count();

        // Mendapatkan produk dengan stok rendah (kurang dari 5 item)
        $this->lowStockItems = Product::where('stock', '<', 5)
            ->where('is_active', true)
            ->count();

        // Mendapatkan pesanan terbaru
        $this->recentOrders = OrderProduct::with(['customer', 'payments'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->order_product_id,
                    'customer' => $order->customer->name,
                    'type' => 'Produk',
                    'status' => $order->status_order,
                    'amount' => $order->grand_total,
                    'date' => $order->created_at->format('d M Y')
                ];
            });

        // Mendapatkan jadwal servis hari ini dengan prioritas kunjungan onsite
        $this->serviceTickets = ServiceTicket::with(['orderService.customer'])
            ->where(function ($query) {
                $query->whereDate('schedule_date', Carbon::today())
                    ->orWhere(function ($subQuery) {
                        $subQuery->whereNotNull('visit_schedule')
                            ->whereDate('visit_schedule', Carbon::today());
                    });
            })
            ->orderByRaw("CASE WHEN visit_schedule IS NOT NULL THEN 0 ELSE 1 END")
            ->orderBy('visit_schedule')
            ->orderBy('schedule_date')
            ->get()
            ->map(function ($ticket) {
                $isVisit = $ticket->visit_schedule && Carbon::parse($ticket->visit_schedule)->isToday();

                return [
                    'id' => $ticket->service_ticket_id,
                    'customer' => $ticket->orderService->customer->name,
                    'status' => $ticket->status,
                    'schedule' => $isVisit
                        ? Carbon::parse($ticket->visit_schedule)->format('H:i')
                        : Carbon::parse($ticket->schedule_date)->format('H:i'),
                    'type' => $ticket->orderService->type,
                    'device' => $ticket->orderService->device,
                    'is_visit' => $isVisit,
                    'visit_time' => $isVisit ? Carbon::parse($ticket->visit_schedule)->format('H:i') : null,
                    'address' => $ticket->orderService->customer->addresses->first()?->address ?? null
                ];
            });

        // Menghitung pendapatan bulanan
        $this->monthlyRevenue = PaymentDetail::where('status', 'dibayar')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('amount');

        // Mendapatkan produk terlaris
        $this->topProducts = Product::orderBy('sold_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($product) {
                return [
                    'name' => $product->name,
                    'sold' => $product->sold_count,
                    'revenue' => $product->price * $product->sold_count
                ];
            });

        // Mendapatkan layanan terpopuler
        $this->topServices = Service::orderBy('sold_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($service) {
                return [
                    'name' => $service->name,
                    'sold' => $service->sold_count,
                    'revenue' => $service->price * $service->sold_count
                ];
            });

        // Data untuk chart pendapatan bulanan (6 bulan terakhir)
        $this->monthlyRevenueChart = $this->getMonthlyRevenueData();

        // Data untuk chart status pesanan
        $this->orderStatusChart = $this->getOrderStatusData();

        // Data untuk chart status servis
        $this->serviceStatusChart = $this->getServiceStatusData();

        // Data untuk chart inventori
        $this->inventoryChart = $this->getInventoryData();

        // Data untuk expired orders dan overdue services
        $this->expiredOrders = $this->getExpiredOrders();
        $this->overdueServices = $this->getOverdueServices();

        // Data untuk chart status pembayaran
        $this->paymentStatusChart = $this->getPaymentStatusData();

        // Data untuk metrik performa servis
        $this->servicePerformanceMetrics = $this->getServicePerformanceMetrics();
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

            $revenue = PaymentDetail::where('status', 'dibayar')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');

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
        $diproses = OrderProduct::where('status_order', 'diproses')->count() +
            OrderService::where('status_order', 'diproses')->count();
        $diantar = OrderProduct::where('status_order', 'diantar')->count() +
            OrderService::where('status_order', 'diantar')->count();
        $selesai = OrderProduct::where('status_order', 'selesai')->count() +
            OrderService::where('status_order', 'selesai')->count();
        $dibatalkan = OrderProduct::where('status_order', 'dibatalkan')->count() +
            OrderService::where('status_order', 'dibatalkan')->count();
        $expired = OrderProduct::where('status_order', 'expired')->count() +
            OrderService::where('status_order', 'expired')->count();

        return [
            'labels' => ['Menunggu', 'Diproses', 'Diantar', 'Selesai', 'Dibatalkan', 'Expired'],
            'data' => [$menunggu, $diproses, $diantar, $selesai, $dibatalkan, $expired],
            'colors' => ['#f59e0b', '#3b82f6', '#8b5cf6', '#10b981', '#ef4444', '#6b7280']
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
        $expired = ServiceTicket::where('status', 'expired')->count();

        return [
            'labels' => ['Menunggu', 'Dijadwalkan', 'Menuju Lokasi', 'Diproses', 'Menunggu Sparepart', 'Siap Diambil', 'Diantar', 'Selesai', 'Dibatalkan', 'Expired'],
            'data' => [$menunggu, $dijadwalkan, $menuju_lokasi, $diproses, $menunggu_sparepart, $siap_diambil, $diantar, $selesai, $dibatalkan, $expired],
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
        $expiredProducts = OrderProduct::where('status_order', 'expired')
            ->orWhere(function ($query) {
                $query->whereNotNull('expired_date')
                    ->where('expired_date', '<', now());
            })
            ->count();

        $expiredServices = OrderService::where('status_order', 'expired')
            ->orWhere(function ($query) {
                $query->whereNotNull('expired_date')
                    ->where('expired_date', '<', now());
            })
            ->count();

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
            ->whereNotIn('status_order', ['selesai', 'dibatalkan', 'expired'])
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

    private function getServicePerformanceMetrics()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $lastMonth = Carbon::now()->subMonth()->month;
        $lastMonthYear = Carbon::now()->subMonth()->year;

        // Current month completion time (average days)
        $currentMonthAvg = OrderService::where('status_order', 'selesai')
            ->whereMonth('updated_at', $currentMonth)
            ->whereYear('updated_at', $currentYear)
            ->whereNotNull('created_at')
            ->selectRaw('AVG(DATEDIFF(updated_at, created_at)) as avg_days')
            ->first()
            ->avg_days ?? 0;

        // Last month completion time
        $lastMonthAvg = OrderService::where('status_order', 'selesai')
            ->whereMonth('updated_at', $lastMonth)
            ->whereYear('updated_at', $lastMonthYear)
            ->whereNotNull('created_at')
            ->selectRaw('AVG(DATEDIFF(updated_at, created_at)) as avg_days')
            ->first()
            ->avg_days ?? 0;

        // On-time vs Late completions (current month)
        $totalCompleted = OrderService::where('status_order', 'selesai')
            ->whereMonth('updated_at', $currentMonth)
            ->whereYear('updated_at', $currentYear)
            ->count();

        $onTimeCompleted = OrderService::where('status_order', 'selesai')
            ->whereMonth('updated_at', $currentMonth)
            ->whereYear('updated_at', $currentYear)
            ->whereRaw('DATEDIFF(updated_at, created_at) <= 7') // Assuming 7 days is on-time
            ->count();

        $lateCompleted = $totalCompleted - $onTimeCompleted;

        return [
            'current_month_avg_days' => round($currentMonthAvg, 1),
            'last_month_avg_days' => round($lastMonthAvg, 1),
            'on_time_count' => $onTimeCompleted,
            'late_count' => $lateCompleted,
            'total_completed' => $totalCompleted
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard-stats');
    }
}
