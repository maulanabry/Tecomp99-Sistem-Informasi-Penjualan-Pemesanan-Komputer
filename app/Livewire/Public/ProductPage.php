<?php

namespace App\Livewire\Public;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class ProductPage extends Component
{
    use WithPagination;

    public $search = '';
    public $minPrice = '';
    public $maxPrice = '';
    public $selectedCategory = '';
    public $sortBy = 'popular';
    public $priceRange = [0, 50000000]; // Default range 0 - 50 juta

    public $categories;
    public $wishlist = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'minPrice' => ['except' => ''],
        'maxPrice' => ['except' => ''],
        'selectedCategory' => ['except' => ''],
        'sortBy' => ['except' => 'popular'],
    ];

    public function mount()
    {
        // Get all categories that have products (fix the filter issue)
        $this->categories = Category::whereHas('products', function ($query) {
            $query->where('is_active', true)->where('stock', '>', 0);
        })
            ->get();

        // Set price range based on actual product prices
        $priceStats = Product::where('is_active', true)
            ->where('stock', '>', 0)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        if ($priceStats) {
            $this->priceRange = [
                (int) $priceStats->min_price,
                (int) $priceStats->max_price
            ];
        }

        // Initialize wishlist from session/cookie if needed
        $this->wishlist = session()->get('wishlist', []);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedMinPrice()
    {
        $this->resetPage();
    }

    public function updatedMaxPrice()
    {
        $this->resetPage();
    }

    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatedSortBy()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->minPrice = '';
        $this->maxPrice = '';
        $this->selectedCategory = '';
        $this->sortBy = 'popular';
        $this->resetPage();
    }

    public function toggleWishlist($productId)
    {
        // Check if customer is logged in
        if (!auth()->guard('web')->check()) {
            session()->flash('auth-message', 'Silakan login terlebih dahulu untuk menambahkan ke wishlist.');
            return;
        }

        if (in_array($productId, $this->wishlist)) {
            $this->wishlist = array_diff($this->wishlist, [$productId]);
            session()->flash('wishlist-message', 'Produk dihapus dari wishlist.');
        } else {
            $this->wishlist[] = $productId;
            session()->flash('wishlist-message', 'Produk ditambahkan ke wishlist.');
        }

        // Store in session
        session()->put('wishlist', $this->wishlist);

        // Emit event for notifications
        $this->dispatch('wishlist-updated', count($this->wishlist));
    }

    public function addToCart($productId)
    {
        // Check if customer is logged in
        if (!auth()->guard('web')->check()) {
            session()->flash('auth-message', 'Silakan login terlebih dahulu untuk menambahkan ke keranjang.');
            return;
        }

        // Add to cart logic here
        // For now, just emit an event
        $this->dispatch('product-added-to-cart', $productId);

        // Show success message
        session()->flash('cart-message', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function render()
    {
        $query = Product::with(['category', 'brand', 'images'])
            ->where('is_active', true)
            ->where('stock', '>', 0);

        // Apply search filter
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        // Apply category filter
        if (!empty($this->selectedCategory)) {
            $query->where('categories_id', $this->selectedCategory);
        }

        // Apply price filters
        if (!empty($this->minPrice) && is_numeric($this->minPrice)) {
            $query->where('price', '>=', $this->minPrice);
        }

        if (!empty($this->maxPrice) && is_numeric($this->maxPrice)) {
            $query->where('price', '<=', $this->maxPrice);
        }

        // Apply sorting
        switch ($this->sortBy) {
            case 'popular':
                $query->orderBy('sold_count', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'highest_price':
                $query->orderBy('price', 'desc');
                break;
            case 'lowest_price':
                $query->orderBy('price', 'asc');
                break;
            default:
                $query->orderBy('sold_count', 'desc');
        }

        $products = $query->paginate(12);

        return view('livewire.public.product-page', [
            'products' => $products
        ]);
    }
}
