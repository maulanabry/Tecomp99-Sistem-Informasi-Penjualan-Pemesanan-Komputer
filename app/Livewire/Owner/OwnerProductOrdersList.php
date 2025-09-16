<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\OrderProduct;

class OwnerProductOrdersList extends Component
{
    public $productOrders = [];

    public function mount()
    {
        $this->loadProductOrders();
    }

    public function loadProductOrders()
    {
        $this->productOrders = OrderProduct::with(['customer.addresses', 'items.product'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                $primaryAddress = $order->customer->addresses->first();
                $address = $primaryAddress ? $primaryAddress->detail_address : 'Alamat tidak tersedia';

                $productNames = $order->items->map(function ($item) {
                    return $item->product->name ?? 'Produk tidak ditemukan';
                })->take(2)->implode(', ');

                if ($order->items->count() > 2) {
                    $productNames .= '...';
                }

                return [
                    'id' => $order->order_product_id,
                    'customer_name' => $order->customer->name ?? 'N/A',
                    'customer_contact' => $order->customer->phone ?? 'N/A',
                    'address' => $address,
                    'products' => $productNames,
                    'items_count' => $order->items->count(),
                    'amount' => $order->grand_total,
                    'status' => $order->status_order,
                    'date' => $order->created_at->format('d M Y'),
                ];
            });
    }

    public function render()
    {
        return view('livewire.owner.owner-product-orders-list');
    }
}
