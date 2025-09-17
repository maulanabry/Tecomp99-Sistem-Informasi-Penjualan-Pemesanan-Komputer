<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\OrderProduct;
use App\Models\OrderService;

class OwnerOrderDistributionChart extends Component
{
    protected $listeners = ['refresh-dashboard' => '$refresh', 'refresh-charts' => 'loadDistributionData'];

    public $distributionData;

    public function mount()
    {
        $this->loadDistributionData();
    }

    public function loadDistributionData()
    {
        $this->distributionData = $this->getOrderDistribution();
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

    public function render()
    {
        return view('livewire.owner.owner-order-distribution-chart');
    }
}
