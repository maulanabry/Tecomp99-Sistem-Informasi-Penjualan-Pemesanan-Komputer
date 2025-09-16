<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;

class LowStockProductsList extends Component
{
    public $lowStockProducts = [];
    public $showAddStockModal = false;
    public $selectedProduct = null;
    public $addStockQuantity = 1;

    protected $listeners = ['refresh-dashboard' => 'loadData'];

    protected $rules = [
        'addStockQuantity' => 'required|integer|min:1'
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->lowStockProducts = Product::with(['category', 'images'])
            ->where('stock', '<=', 5)
            ->where('is_active', true)
            ->orderBy('stock', 'asc')
            ->take(5)
            ->get();
    }

    public function showAddStockModal($productId)
    {
        $this->selectedProduct = Product::find($productId);
        $this->showAddStockModal = true;
        $this->addStockQuantity = 1;
    }

    public function closeAddStockModal()
    {
        $this->showAddStockModal = false;
        $this->selectedProduct = null;
        $this->addStockQuantity = 1;
        $this->resetErrorBag();
    }

    public function addStock()
    {
        $this->validate();

        if ($this->selectedProduct) {
            $this->selectedProduct->increment('stock', $this->addStockQuantity);

            session()->flash('stock_message', "Stok {$this->selectedProduct->name} berhasil ditambah {$this->addStockQuantity} unit");
            session()->flash('stock_type', 'success');

            $this->closeAddStockModal();
            $this->loadData();
        }
    }

    public function render()
    {
        return view('livewire.admin.low-stock-products-list');
    }
}
