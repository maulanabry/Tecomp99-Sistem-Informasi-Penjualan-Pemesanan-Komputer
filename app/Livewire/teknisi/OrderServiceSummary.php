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
        // Create base query for assigned order services
        $baseQuery = OrderService::query()
            ->whereHas('tickets', function ($query) {
                $query->where('admin_id', Auth::id());
            })
            ->with(['tickets' => function ($query) {
                $query->where('admin_id', Auth::id());
            }]);

        // Clone the base query for different counts to prevent query pollution
        $totalOrderService = (clone $baseQuery)->count();

        // Type counts
        $reguler = (clone $baseQuery)->where('type', 'reguler')->count();
        $onsite = (clone $baseQuery)->where('type', 'onsite')->count();

        // Order status counts
        $orderMenunggu = (clone $baseQuery)->where('status_order', 'Menunggu')->count();
        $orderDiproses = (clone $baseQuery)->where('status_order', 'Diproses')->count();
        $orderSelesai = (clone $baseQuery)->where('status_order', 'Selesai')->count();
        $orderDibatalkan = (clone $baseQuery)->where('status_order', 'Dibatalkan')->count();

        // Payment status counts
        $orderBelumDibayar = (clone $baseQuery)->where('status_payment', 'belum_dibayar')->count();
        $orderLunas = (clone $baseQuery)->where('status_payment', 'lunas')->count();

        // Calculate total income from completed and paid orders
        $pendapatan = (clone $baseQuery)
            ->where('status_payment', 'lunas')
            ->where('status_order', 'Selesai')
            ->sum('grand_total');

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
