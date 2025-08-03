<?php

namespace App\Livewire\Owner;

use App\Models\OrderService;
use Livewire\Component;

class OrderServiceSummary extends Component
{
    public function render()
    {
        $totalOrderService = OrderService::count();
        $pendingOrderService = OrderService::where('status_order', 'Menunggu')->count();
        $processingOrderService = OrderService::where('status_order', 'Diproses')->count();
        $completedOrderService = OrderService::where('status_order', 'Selesai')->count();
        $cancelledOrderService = OrderService::where('status_order', 'Dibatalkan')->count();

        return view('livewire.owner.order-service-summary', [
            'totalOrderService' => $totalOrderService,
            'pendingOrderService' => $pendingOrderService,
            'processingOrderService' => $processingOrderService,
            'completedOrderService' => $completedOrderService,
            'cancelledOrderService' => $cancelledOrderService,
        ]);
    }
}
