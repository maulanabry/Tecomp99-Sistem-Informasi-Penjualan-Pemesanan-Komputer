<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Product;

class ProductTable extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $selectedProductId = null;
    public $search = '';
    public $categoryFilter = '';
    public $brandFilter = '';
    public $statusFilter = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $deleteId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'brandFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingBrandFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    public function isModalOpen()
    {
        return $this->isModalOpen;
    }

    public function confirmDelete($productId = null)
    {
        if ($productId === null) {
            session()->flash('error', 'ID produk tidak diberikan.');
            return;
        }

        $product = Product::find($productId);
        if ($product) {
            $this->selectedProductId = $productId;
            $this->isModalOpen = true;
        } else {
            session()->flash('error', 'Produk tidak ditemukan.');
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->selectedProductId = null;
    }

    public function deleteProduct($productId = null)
    {
        try {
            $product = Product::find($productId);
            if (!$product) {
                session()->flash('error', 'Produk tidak ditemukan.');
                return;
            }

            $product->delete();
            $this->isModalOpen = false;
            $this->selectedProductId = null;
            $this->deleteId = null;
            session()->flash('success', 'Produk berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus produk.');
        }
    }

    public function render()
    {
        $products = Product::query()
            ->with(['category', 'brand'])
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('categories_id', $this->categoryFilter);
            })
            ->when($this->brandFilter, function ($query) {
                $query->where('brand_id', $this->brandFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter === 'active');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.product-table', [
            'products' => $products
        ]);
    }
}
