<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\OrderProduct;

class ProductOrdersList extends Component
{
    public $productOrders;

    protected $listeners = ['refresh-dashboard' => 'loadData'];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Tab 1: Pesanan Produk (≠ selesai & ≠ dibatalkan, prioritas menunggu & diproses)
        $this->productOrders = OrderProduct::with(['customer', 'items.product'])
            ->whereNotIn('status_order', ['selesai', 'dibatalkan'])
            ->orderByRaw("CASE 
                WHEN status_order = 'menunggu' THEN 1 
                WHEN status_order = 'diproses' THEN 2 
                ELSE 3 END")
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->order_product_id,
                    'customer' => $order->customer->name,
                    'status' => $order->status_order,
                    'payment_status' => $order->status_payment,
                    'amount' => $order->grand_total,
                    'date' => $order->created_at->format('d M Y'),
                    'items_count' => $order->items->count()
                ];
            });
    }

    public function render()
    {
        return view('livewire.admin.product-orders-list');
    }
}
