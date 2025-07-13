<?php

namespace App\Livewire\Public;

use App\Models\Product;
use Livewire\Component;

class BestSellingProducts extends Component
{
    public $products;

    public function mount()
    {
        // Ambil produk terlaris berdasarkan sold_count
        $this->products = Product::with(['category', 'brand', 'images'])
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('sold_count', 'desc')
            ->limit(8)
            ->get();
    }

    public function render()
    {
        return view('livewire.public.best-selling-products');
    }
}
