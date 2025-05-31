<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Livewire\WithPagination;

class AddProductItemsModal extends Component
{
    use WithPagination;

    public $show = false;
    public $searchQuery = '';
    public $selectedCategory = '';
    public $selectedBrand = '';

    protected $listeners = ['openModal' => 'open', 'closeModal' => 'close'];

    public function mount()
    {
        $this->resetPage();
    }

    public function updatedSearchQuery()
    {
        $this->resetPage();
    }

    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatedSelectedBrand()
    {
        $this->resetPage();
    }

    public function open()
    {
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
        $this->reset(['searchQuery', 'selectedCategory', 'selectedBrand']);
        $this->resetPage();
    }

    public function addProduct($productId)
    {
        $this->dispatch('productSelected', productId: $productId);
        $this->close();
    }

    public function getProductsProperty()
    {
        return Product::query()
            ->with(['category:categories_id,name', 'brand:brand_id,name'])
            ->where('is_active', true)
            ->where('stock', '>', 5)
            ->when($this->searchQuery, function ($query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%');
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('categories_id', $this->selectedCategory);
            })
            ->when($this->selectedBrand, function ($query) {
                $query->where('brand_id', $this->selectedBrand);
            })
            ->orderBy('name')
            ->paginate(12);
    }

    public function getCategoriesProperty()
    {
        return Category::orderBy('name')
            ->whereHas('products', function ($query) {
                $query->where('is_active', true)
                    ->where('stock', '>', 5);
            })
            ->get();
    }

    public function getBrandsProperty()
    {
        return Brand::orderBy('name')
            ->whereHas('products', function ($query) {
                $query->where('is_active', true)
                    ->where('stock', '>', 5);
            })
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.add-product-items-modal', [
            'products' => $this->products,
            'categories' => $this->categories,
            'brands' => $this->brands,
        ]);
    }
}
