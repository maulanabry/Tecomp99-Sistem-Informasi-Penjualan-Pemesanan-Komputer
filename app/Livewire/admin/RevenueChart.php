<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\OrderProduct;
use App\Models\OrderService;
use Carbon\Carbon;

class RevenueChart extends Component
{
    public $revenueFilter = 'monthly'; // daily, monthly, yearly
    public $revenueChart;

    protected $listeners = ['refresh-dashboard' => 'calculateRevenueChart'];

    public function mount()
    {
        $this->revenueFilter = request('filter', 'monthly');
        $this->calculateRevenueChart();
    }

    public function setRevenueFilter($filter)
    {
        $this->revenueFilter = $filter;
        $this->calculateRevenueChart();
    }

    public function calculateRevenueChart()
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

    public function render()
    {
        return view('livewire.admin.revenue-chart');
    }
}
