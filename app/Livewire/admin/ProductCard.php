<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;

class ProductCard extends Component
{
    public Product $product;
    public $loading = false;

    public function addToOrder()
    {
        $this->loading = true;
        $this->dispatch('productSelected', [
            'id' => $this->product->product_id,
            'name' => $this->product->name,
            'price' => $this->product->price,
            'weight' => $this->product->weight
        ]);
        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.admin.product-card', [
            'formattedPrice' => 'Rp ' . number_format($this->product->price, 0, ',', '.'),
        ]);
    }
}
