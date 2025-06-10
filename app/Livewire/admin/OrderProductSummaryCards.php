<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\OrderProduct;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderProductSummaryCards extends Component
{
    public $totalOrderProduk;
    public $totalRevenue;
    public $orderLangsung;
    public $orderPengiriman;
    public $completedOrders;
    public $canceledOrders;
    public $unpaidOrders;
    public $paidOrders;
    public $showAllCards = false;

    public function mount()
    {
        $this->refreshCounts();
    }

    public function toggleCards()
    {
        $this->showAllCards = !$this->showAllCards;
    }

    public function refreshCounts()
    {
        try {
            // Menghitung total order produk
            $this->totalOrderProduk = OrderProduct::count();

            // Menghitung total revenue dari grand_total dengan status pembayaran 'lunas'
            $this->totalRevenue = OrderProduct::where('status_payment', 'lunas')->sum('grand_total');

            // Menghitung jumlah order dengan tipe 'langsung'
            $this->orderLangsung = OrderProduct::where('type', 'langsung')->count();

            // Menghitung jumlah order dengan tipe 'pengiriman'
            $this->orderPengiriman = OrderProduct::where('type', 'pengiriman')->count();

            // Menghitung jumlah order dengan status order 'selesai'
            $this->completedOrders = OrderProduct::where('status_order', 'selesai')->count();

            // Menghitung jumlah order dengan status order 'dibatalkan'
            $this->canceledOrders = OrderProduct::where('status_order', 'dibatalkan')->count();

            // Menghitung jumlah order dengan status pembayaran 'belum_dibayar'
            $this->unpaidOrders = OrderProduct::where('status_payment', 'belum_dibayar')->count();

            // Menghitung jumlah order dengan status pembayaran 'lunas'
            $this->paidOrders = OrderProduct::where('status_payment', 'lunas')->count();
        } catch (Exception $e) {
            Log::error('Error fetching order product counts: ' . $e->getMessage());
            $this->totalOrderProduk = 0;
            $this->totalRevenue = 0;
            $this->orderLangsung = 0;
            $this->orderPengiriman = 0;
            $this->completedOrders = 0;
            $this->canceledOrders = 0;
            $this->unpaidOrders = 0;
            $this->paidOrders = 0;
        }
    }

    public function render()
    {
        return view('livewire.admin.order-product-summary-cards');
    }
}
