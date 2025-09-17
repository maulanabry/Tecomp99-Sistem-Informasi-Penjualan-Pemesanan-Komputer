<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentDetail;
use App\Models\OrderProduct;
use App\Models\OrderService;
use Carbon\Carbon;

class OwnerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $analyticsTab = $request->query('analytics_tab', 'tren-pendapatan');
        $revenueFilter = $request->query('revenue_filter', 'monthly');

        $viewData = [
            'analyticsTab' => $analyticsTab,
            'revenueFilter' => $revenueFilter,
            'revenueChart' => $this->getRevenueData($revenueFilter),
            'distributionData' => $this->getOrderDistribution(),
            'paymentStatusData' => $this->getPaymentStatusData(),
            'overduePayments' => $this->getOverduePayments(),
        ];

        return view('owner.dashboard', $viewData);
    }

    private function getRevenueData($filter)
    {
        $now = Carbon::now();
        $labels = [];
        $revenues = [];
        $totalRevenue = 0;
        $totalOrders = 0;

        switch ($filter) {
            case 'daily':
                for ($i = 6; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i);
                    $labels[] = $date->format('d M');
                    $revenue = PaymentDetail::where('status', 'dibayar')->whereDate('created_at', $date->toDateString())->sum('amount');
                    $revenues[] = $revenue;
                    $totalRevenue += $revenue;
                }
                $totalOrders = PaymentDetail::where('status', 'dibayar')->where('created_at', '>=', $now->copy()->subDays(7))->count();
                break;
            case 'monthly':
                for ($i = 5; $i >= 0; $i--) {
                    $date = $now->copy()->subMonths($i);
                    $labels[] = $date->format('M Y');
                    $revenue = PaymentDetail::where('status', 'dibayar')->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->sum('amount');
                    $revenues[] = $revenue;
                    $totalRevenue += $revenue;
                }
                $totalOrders = PaymentDetail::where('status', 'dibayar')->where('created_at', '>=', $now->copy()->subMonths(6))->count();
                break;
            case 'yearly':
                for ($i = 2; $i >= 0; $i--) {
                    $date = $now->copy()->subYears($i);
                    $labels[] = $date->format('Y');
                    $revenue = PaymentDetail::where('status', 'dibayar')->whereYear('created_at', $date->year)->sum('amount');
                    $revenues[] = $revenue;
                    $totalRevenue += $revenue;
                }
                $totalOrders = PaymentDetail::where('status', 'dibayar')->where('created_at', '>=', $now->copy()->subYears(3))->count();
                break;
        }

        return [
            'labels' => $labels,
            'revenues' => $revenues,
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders
        ];
    }

    private function getOrderDistribution()
    {
        $productOrders = OrderProduct::count();
        $serviceOrders = OrderService::count();
        $totalOrders = $productOrders + $serviceOrders;
        $productRevenue = OrderProduct::sum('grand_total');
        $serviceRevenue = OrderService::sum('grand_total');
        $totalRevenue = $productRevenue + $serviceRevenue;

        return [
            'labels' => ['Produk', 'Servis'],
            'orders' => [$productOrders, $serviceOrders],
            'revenues' => [$productRevenue, $serviceRevenue],
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'product_percentage' => $totalOrders > 0 ? round(($productOrders / $totalOrders) * 100, 1) : 0,
            'service_percentage' => $totalOrders > 0 ? round(($serviceOrders / $totalOrders) * 100, 1) : 0
        ];
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

    private function getOverduePayments()
    {
        $now = Carbon::now();
        $overdueProductOrders = OrderProduct::with(['customer'])
            ->where('status_order', '!=', 'completed')
            ->whereHas('paymentDetails', function ($query) {
                $query->where('status', 'belum_dibayar');
            })->get()->map(function ($order) use ($now) {
                return [
                    'order_id' => $order->order_product_id,
                    'customer_name' => $order->customer->name,
                    'amount' => $order->paymentDetails->where('status', 'belum_dibayar')->sum('amount'),
                    'type' => 'product'
                ];
            });

        $overdueServiceOrders = OrderService::with(['customer'])
            ->where('status_order', '!=', 'completed')
            ->whereHas('paymentDetails', function ($query) {
                $query->where('status', 'belum_dibayar');
            })->get()->map(function ($order) use ($now) {
                return [
                    'order_id' => $order->order_service_id,
                    'customer_name' => $order->customer->name,
                    'amount' => $order->paymentDetails->where('status', 'belum_dibayar')->sum('amount'),
                    'type' => 'service'
                ];
            });

        return $overdueProductOrders->concat($overdueServiceOrders)
            ->sortByDesc('amount')
            ->take(10)
            ->values();
    }
}
