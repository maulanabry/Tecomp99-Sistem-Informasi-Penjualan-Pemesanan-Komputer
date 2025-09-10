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


    // New metrics for updated dashboard
    public $totalRevenueCurrentMonth;
    public $totalRevenuePreviousMonth;
    public $revenueChangePercentage;
    public $ordersInProgress;
    public $totalDownPayment;
    public $totalInstallments;
    public $newCustomers;
    public $completedServicesNotCollected;

    public function mount()
    {
        $this->calculateStats();
    }



    public function calculateStats()
    {
        // Menghitung total pendapatan dari orders dengan status_payment = lunas
        $this->totalRevenue = OrderProduct::where('status_payment', 'lunas')
            ->sum('grand_total') +
            OrderService::where('status_payment', 'lunas')
            ->sum('grand_total');

        // Menghitung total pendapatan bulan ini
        $this->totalRevenueCurrentMonth = OrderProduct::where('status_payment', 'lunas')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->sum('grand_total') +
            OrderService::where('status_payment', 'lunas')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->sum('grand_total');

        // Menghitung total pendapatan bulan lalu
        $this->totalRevenuePreviousMonth = OrderProduct::where('status_payment', 'lunas')
            ->whereMonth('updated_at', Carbon::now()->subMonth()->month)
            ->whereYear('updated_at', Carbon::now()->subMonth()->year)
            ->sum('grand_total') +
            OrderService::where('status_payment', 'lunas')
            ->whereMonth('updated_at', Carbon::now()->subMonth()->month)
            ->whereYear('updated_at', Carbon::now()->subMonth()->year)
            ->sum('grand_total');

        // Menghitung persentase perubahan
        if ($this->totalRevenuePreviousMonth > 0) {
            $this->revenueChangePercentage = (($this->totalRevenueCurrentMonth - $this->totalRevenuePreviousMonth) / $this->totalRevenuePreviousMonth) * 100;
        } else {
            $this->revenueChangePercentage = $this->totalRevenueCurrentMonth > 0 ? 100 : 0;
        }

        // Menghitung pesanan yang menunggu
        $this->pendingOrders = OrderProduct::where('status_order', 'menunggu')
            ->count() +
            OrderService::where('status_order', 'menunggu')
            ->count();

        // Menghitung pesanan yang sedang diproses
        $this->ordersInProgress = OrderProduct::where('status_order', 'diproses')
            ->count() +
            OrderService::where('status_order', 'diproses')
            ->count();

        // Menghitung tiket servis yang aktif
        $this->activeTickets = ServiceTicket::whereNotIn('status', ['selesai', 'dibatalkan'])
            ->count();

        // Mendapatkan produk dengan stok rendah (kurang dari 5 item)
        $this->lowStockItems = Product::where('stock', '<', 5)
            ->where('is_active', true)
            ->count();

        // Menghitung total down payment
        $this->totalDownPayment = [
            'count' => PaymentDetail::where('payment_type', 'down_payment')
                ->where('status', 'dibayar')
                ->count(),
            'amount' => PaymentDetail::where('payment_type', 'down_payment')
                ->where('status', 'dibayar')
                ->sum('amount')
        ];

        // Menghitung total cicilan
        $this->totalInstallments = [
            'count' => PaymentDetail::where('payment_type', 'cicilan')
                ->where('status', 'dibayar')
                ->count(),
            'amount' => PaymentDetail::where('payment_type', 'cicilan')
                ->where('status', 'dibayar')
                ->sum('amount')
        ];

        // Menghitung pelanggan baru bulan ini
        $this->newCustomers = Customer::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Menghitung servis selesai tapi belum dikumpulkan
        $this->completedServicesNotCollected = OrderService::where('status_order', 'selesai')
            ->where('status_payment', '!=', 'lunas')
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

        // Data untuk melewati_jatuh_tempo orders dan overdue services
        $this->expiredOrders = $this->getExpiredOrders();
        $this->overdueServices = $this->getOverdueServices();

        // Data untuk chart status pembayaran
        $this->paymentStatusChart = $this->getPaymentStatusData();
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
        $melewati_jatuh_tempo = OrderProduct::where('status_order', 'melewati_jatuh_tempo')->count() +
            OrderService::where('status_order', 'melewati_jatuh_tempo')->count();

        return [
            'labels' => ['Menunggu', 'Inden', 'Siap Kirim', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan', 'Melewati Jatuh Tempo'],
            'data' => [$menunggu, $inden, $siap_kirim, $diproses, $dikirim, $selesai, $dibatalkan, $melewati_jatuh_tempo],
            'colors' => ['#f59e0b', '#ea580c', '#9333ea', '#3b82f6', '#8b5cf6', '#10b981', '#ef4444', '#6b7280']
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
        $expiredProducts = OrderProduct::where('status_order', 'melewati_jatuh_tempo')
            ->orWhere(function ($query) {
                $query->whereNotNull('expired_date')
                    ->where('expired_date', '<', now());
            })
            ->count();

        $expiredServices = OrderService::where('status_order', 'melewati_jatuh_tempo')
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
            ->whereNotIn('status_order', ['selesai', 'dibatalkan', 'melewati_jatuh_tempo'])
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
