<?php

namespace App\Livewire\Teknisi;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\OrderService;
use Illuminate\Support\Facades\Auth;

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
        // Get order services that have tickets assigned to the current teknisi
        $assignedOrderServices = OrderService::whereHas('tickets', function ($query) {
            $query->where('admin_id', Auth::id());
        });

        $totalOrderService = $assignedOrderServices->count();
        $reguler = $assignedOrderServices->where('type', 'reguler')->count();
        $onsite = $assignedOrderServices->where('type', 'onsite')->count();
        $orderMenunggu = $assignedOrderServices->where('status_order', 'Menunggu')->count();
        $orderDiproses = $assignedOrderServices->where('status_order', 'Diproses')->count();
        $orderSelesai = $assignedOrderServices->where('status_order', 'Selesai')->count();
        $orderDibatalkan = $assignedOrderServices->where('status_order', 'Dibatalkan')->count();
        $orderBelumDibayar = $assignedOrderServices->where('status_payment', 'belum_dibayar')->count();
        $orderLunas = $assignedOrderServices->where('status_payment', 'lunas')->count();
        $pendapatan = $assignedOrderServices->where('status_payment', 'lunas')->sum('grand_total');

        return view('livewire.teknisi.order-service-summary', [
            'totalOrderService' => $totalOrderService,
            'reguler' => $reguler,
            'onsite' => $onsite,
            'orderMenunggu' => $orderMenunggu,
            'orderDiproses' => $orderDiproses,
            'orderSelesai' => $orderSelesai,
            'orderDibatalkan' => $orderDibatalkan,
            'orderBelumDibayar' => $orderBelumDibayar,
            'orderLunas' => $orderLunas,
            'pendapatan' => $pendapatan,
            'showAllCards' => $this->showAllCards,
        ]);
    }
}
