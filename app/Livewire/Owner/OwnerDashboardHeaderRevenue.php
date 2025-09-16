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
        $now = Carbon::now();
        $this->totalRevenue = PaymentDetail::where('status', 'dibayar')
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->sum('amount');
    }

    public function render()
    {
        return view('livewire.owner.owner-dashboard-header-revenue');
    }
}
