<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\OrderProduct;
use App\Models\OrderService;
use App\Models\PaymentDetail;
use App\Models\Admin;
use App\Models\ServiceTicket;
use Carbon\Carbon;

class OwnerDashboardStats extends Component
{
    public $totalProductOrders;
    public $totalServiceOrders;
    public $totalRevenueThisMonth;
    public $totalTechnicians;
    public $totalAdmins;
    public $monthlyProductSalesChart;
    public $monthlyServiceOrdersChart;
    public $recentProductOrders;
    public $recentServiceOrders;
    public $technicianOverview;

    public function mount()
    {
        $this->calculateStats();
    }

    public function calculateStats()
    {
        // Summary Cards
        $this->totalProductOrders = OrderProduct::count();
        $this->totalServiceOrders = OrderService::count();

        // Total Revenue This Month
        $this->totalRevenueThisMonth = PaymentDetail::where('status', 'dibayar')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount');

        // Total Technicians (Admin with role teknisi)
        $this->totalTechnicians = Admin::where('role', 'teknisi')->count();

        // Total Admins (Admin with role admin)
        $this->totalAdmins = Admin::where('role', 'admin')->count();

        // Charts Data
        $this->monthlyProductSalesChart = $this->getMonthlyProductSalesData();
        $this->monthlyServiceOrdersChart = $this->getMonthlyServiceOrdersData();

        // Recent Activities
        $this->recentProductOrders = $this->getRecentProductOrders();
        $this->recentServiceOrders = $this->getRecentServiceOrders();

        // Technician Overview
        $this->technicianOverview = $this->getTechnicianOverview();
    }

    public function refreshDashboard()
    {
        $this->calculateStats();
    }

    private function getMonthlyProductSalesData()
    {
        $months = [];
        $sales = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');

            $monthlySales = OrderProduct::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('grand_total');

            $sales[] = $monthlySales;
        }

        return [
            'labels' => $months,
            'data' => $sales
        ];
    }

    private function getMonthlyServiceOrdersData()
    {
        $months = [];
        $orders = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');

            $monthlyOrders = OrderService::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $orders[] = $monthlyOrders;
        }

        return [
            'labels' => $months,
            'data' => $orders
        ];
    }

    private function getRecentProductOrders()
    {
        return OrderProduct::with(['customer'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'order_code' => $order->order_product_id,
                    'customer_name' => $order->customer->name,
                    'status' => $order->status_order,
                    'total' => $order->grand_total,
                    'date' => $order->created_at->format('d M Y')
                ];
            });
    }

    private function getRecentServiceOrders()
    {
        return OrderService::with(['customer'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'order_code' => $order->order_service_id,
                    'customer_name' => $order->customer->name,
                    'status' => $order->status_order,
                    'total' => $order->grand_total,
                    'date' => $order->created_at->format('d M Y')
                ];
            });
    }

    private function getTechnicianOverview()
    {
        $technicians = Admin::where('role', 'teknisi')->get();

        return $technicians->map(function ($technician) {
            $assignedJobs = ServiceTicket::where('admin_id', $technician->id)->count();

            $statusBreakdown = [
                'pending' => ServiceTicket::where('admin_id', $technician->id)
                    ->where('status', 'Menunggu')->count(),
                'in_progress' => ServiceTicket::where('admin_id', $technician->id)
                    ->where('status', 'Diproses')->count(),
                'completed' => ServiceTicket::where('admin_id', $technician->id)
                    ->where('status', 'Selesai')->count(),
            ];

            return [
                'id' => $technician->id,
                'name' => $technician->name,
                'email' => $technician->email,
                'assigned_jobs' => $assignedJobs,
                'status_breakdown' => $statusBreakdown,
                'is_online' => $technician->isOnline()
            ];
        });
    }

    public function render()
    {
        return view('livewire.owner.owner-dashboard-stats');
    }
}
