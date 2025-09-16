<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\OrderProduct;
use App\Models\OrderService;
use Carbon\Carbon;

class AdminDashboard extends Component
{
    public $totalRevenueCurrentMonth;

    public function mount()
    {
        $this->calculateHeaderStats();
    }

    private function calculateHeaderStats()
    {
        // Total Pendapatan Bulan Ini (for header)
        $this->totalRevenueCurrentMonth = OrderProduct::where('status_payment', 'lunas')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->sum('grand_total') +
            OrderService::where('status_payment', 'lunas')
            ->whereMonth('updated_at', Carbon::now()->month)
            ->whereYear('updated_at', Carbon::now()->year)
            ->sum('grand_total');
    }

    public function refreshDashboard()
    {
        $this->calculateHeaderStats();
        $this->dispatch('refresh-dashboard');
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard');
    }
}
