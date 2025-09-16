<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\OrderProduct;
use App\Models\OrderService;
use Carbon\Carbon;

class ExpiredOrdersList extends Component
{
    public $expiredOrders = [];

    protected $listeners = ['refresh-dashboard' => 'loadData'];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $productOrders = OrderProduct::with('customer')
            ->whereNotNull('expired_date')
            ->whereNotIn('status_order', ['selesai', 'dibatalkan'])
            ->orderBy('expired_date', 'asc')
            ->take(3)
            ->get();

        $serviceOrders = OrderService::with('customer')
            ->whereNotNull('expired_date')
            ->whereNotIn('status_order', ['selesai', 'dibatalkan'])
            ->orderBy('expired_date', 'asc')
            ->take(2)
            ->get();

        $this->expiredOrders = collect()
            ->merge($productOrders)
            ->merge($serviceOrders)
            ->sortBy('expired_date')
            ->take(5)
            ->values();
    }



    public function extendDeadline($orderId, $type)
    {
        // Logic to extend deadline
        session()->flash('expired_message', 'Batas waktu berhasil diperpanjang');
        session()->flash('expired_type', 'success');
        $this->loadData();
    }

    public function sendReminder($orderId, $type)
    {
        // Logic to send reminder
        session()->flash('expired_message', 'Pengingat berhasil dikirim');
        session()->flash('expired_type', 'success');
        $this->loadData();
    }

    public function showAllExpired()
    {
        // Logic to show all expired orders
        return redirect()->route('admin.orders.expired');
    }

    public function render()
    {
        return view('livewire.admin.expired-orders-list');
    }
}
