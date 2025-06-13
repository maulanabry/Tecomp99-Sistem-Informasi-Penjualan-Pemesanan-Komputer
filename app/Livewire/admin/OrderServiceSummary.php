<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\OrderService;

class OrderServiceSummary extends Component
{
    use WithPagination;
    public $showAllCards = false;

    public function toggleCards()
    {
        $this->showAllCards = !$this->showAllCards;
        $this->dispatch('orderServiceSummaryToggled', $this->showAllCards);
    }

    public function render()
    {
        $totalOrderService = OrderService::count();
        $pendapatan = OrderService::where('status_payment', 'lunas')->sum('grand_total');
        $reguler = OrderService::where('type', 'reguler')->count();
        $onsite = OrderService::where('type', 'onsite')->count();
        $orderSelesai = OrderService::where('status_order', 'Selesai')->count();
        $orderDibatalkan = OrderService::where('status_order', 'Dibatalkan')->count();
        $orderBelumDibayar = OrderService::where('status_payment', 'belum_dibayar')->count();
        $orderLunas = OrderService::where('status_payment', 'lunas')->count();

        return view('livewire.admin.order-service-summary', [
            'totalOrderService' => $totalOrderService,
            'pendapatan' => $pendapatan,
            'reguler' => $reguler,
            'onsite' => $onsite,
            'orderSelesai' => $orderSelesai,
            'orderDibatalkan' => $orderDibatalkan,
            'orderBelumDibayar' => $orderBelumDibayar,
            'orderLunas' => $orderLunas,
            'showAllCards' => $this->showAllCards,
        ]);
    }
}
