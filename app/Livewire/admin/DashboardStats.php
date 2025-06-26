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
        $this->pendingOrders = OrderProduct::where('status_order', 'pending')
            ->count() +
            OrderService::where('status_order', 'pending')
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
        $pending = OrderProduct::where('status_order', 'pending')->count() +
            OrderService::where('status_order', 'pending')->count();
        $processing = OrderProduct::where('status_order', 'processing')->count() +
            OrderService::where('status_order', 'processing')->count();
        $completed = OrderProduct::where('status_order', 'completed')->count() +
            OrderService::where('status_order', 'completed')->count();

        return [
            'labels' => ['Menunggu', 'Diproses', 'Selesai'],
            'data' => [$pending, $processing, $completed],
            'colors' => ['#f59e0b', '#3b82f6', '#10b981']
        ];
    }

    private function getServiceStatusData()
    {
        $waiting = ServiceTicket::where('status', 'Menunggu')->count();
        $inProgress = ServiceTicket::where('status', 'Diproses')->count();
        $completed = ServiceTicket::where('status', 'Selesai')->count();

        return [
            'labels' => ['Menunggu', 'Diproses', 'Selesai'],
            'data' => [$waiting, $inProgress, $completed],
            'colors' => ['#f59e0b', '#3b82f6', '#10b981']
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

    public function render()
    {
        return view('livewire.admin.dashboard-stats');
    }
}
