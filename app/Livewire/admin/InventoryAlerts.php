<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;

class InventoryAlerts extends Component
{
    public $lowStockProducts;
    public $outOfStockProducts;

    public function mount()
    {
        $this->loadInventoryData();
    }

    public function loadInventoryData()
    {
        // Mendapatkan produk dengan stok menipis (kurang dari 5 item)
        $this->lowStockProducts = Product::where('stock', '>', 0)
            ->where('stock', '<', 5)
            ->where('is_active', true)
            ->with(['category', 'brand'])
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->product_id,
                    'name' => $product->name,
                    'stock' => $product->stock,
                    'category' => $product->category->name ?? 'N/A',
                    'brand' => $product->brand->name ?? 'N/A',
                    'price' => $product->price
                ];
            });

        // Mendapatkan produk yang habis stok
        $this->outOfStockProducts = Product::where('stock', '=', 0)
            ->where('is_active', true)
            ->with(['category', 'brand'])
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->product_id,
                    'name' => $product->name,
                    'category' => $product->category->name ?? 'N/A',
                    'brand' => $product->brand->name ?? 'N/A',
                    'price' => $product->price
                ];
            });
    }

    public function render()
    {
        return view('livewire.admin.inventory-alerts');
    }
}
