<?php

namespace App\Livewire\Public;

use App\Models\Product;
use Livewire\Component;

class RelatedProducts extends Component
{
    public $productId;

    public function render()
    {
        $product = Product::find($this->productId);

        if (!$product) {
            return view('livewire.public.related-products', [
                'relatedProducts' => collect()
            ]);
        }

        $relatedProducts = Product::where('categories_id', $product->categories_id)
            ->where('product_id', '!=', $this->productId)
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('livewire.public.related-products', [
            'relatedProducts' => $relatedProducts
        ]);
    }
}
