<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\PaymentDetail;
use Carbon\Carbon;

class OwnerDashboardHeaderRevenue extends Component
{
    public $totalRevenue;

    public function mount()
    {
        $this->calculateRevenue();
    }

    public function calculateRevenue()
    {
        $orderServiceTotal = \App\Models\OrderService::where('status_payment', 'lunas')->sum('grand_total');
        $orderProductTotal = \App\Models\OrderProduct::where('status_payment', 'lunas')->sum('grand_total');
        $this->totalRevenue = $orderServiceTotal + $orderProductTotal;
    }

    public function render()
    {
        return view('livewire.owner.owner-dashboard-header-revenue');
    }
}
