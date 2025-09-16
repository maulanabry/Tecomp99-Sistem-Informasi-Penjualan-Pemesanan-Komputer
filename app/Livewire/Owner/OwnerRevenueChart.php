<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\PaymentDetail;
use Carbon\Carbon;

class OwnerRevenueChart extends Component
{
    protected $listeners = ['refresh-dashboard' => '$refresh'];

    public $revenueFilter = 'monthly';
    public $revenueChart;

    public function mount()
    {
        $this->loadRevenueData();
    }

    public function updatedRevenueFilter()
    {
        $this->loadRevenueData();
    }

    public function loadRevenueData()
    {
        $this->revenueChart = $this->getRevenueData();
    }

    private function getRevenueData()
    {
        $now = Carbon::now();
        $labels = [];
        $revenues = [];
        $totalRevenue = 0;
        $totalOrders = 0;

        switch ($this->revenueFilter) {
            case 'daily':
                // Last 7 days
                for ($i = 6; $i >= 0; $i--) {
                    $date = $now->copy()->subDays($i);
                    $labels[] = $date->format('d M');

                    $revenue = PaymentDetail::where('status', 'dibayar')
                        ->whereDate('created_at', $date->toDateString())
                        ->sum('amount');

                    $revenues[] = $revenue;
                    $totalRevenue += $revenue;
                }
                $totalOrders = PaymentDetail::where('status', 'dibayar')
                    ->where('created_at', '>=', $now->copy()->subDays(7))
                    ->count();
                break;

            case 'monthly':
                // Last 6 months
                for ($i = 5; $i >= 0; $i--) {
                    $date = $now->copy()->subMonths($i);
                    $labels[] = $date->format('M Y');

                    $revenue = PaymentDetail::where('status', 'dibayar')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->sum('amount');

                    $revenues[] = $revenue;
                    $totalRevenue += $revenue;
                }
                $totalOrders = PaymentDetail::where('status', 'dibayar')
                    ->where('created_at', '>=', $now->copy()->subMonths(6))
                    ->count();
                break;

            case 'yearly':
                // Last 3 years
                for ($i = 2; $i >= 0; $i--) {
                    $date = $now->copy()->subYears($i);
                    $labels[] = $date->format('Y');

                    $revenue = PaymentDetail::where('status', 'dibayar')
                        ->whereYear('created_at', $date->year)
                        ->sum('amount');

                    $revenues[] = $revenue;
                    $totalRevenue += $revenue;
                }
                $totalOrders = PaymentDetail::where('status', 'dibayar')
                    ->where('created_at', '>=', $now->copy()->subYears(3))
                    ->count();
                break;
        }

        return [
            'labels' => $labels,
            'revenues' => $revenues,
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders
        ];
    }

    public function render()
    {
        return view('livewire.owner.owner-revenue-chart');
    }
}
