<?php

namespace App\Livewire\Public;

use App\Models\Service;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class ServicePage extends Component
{
    use WithPagination;

    public $search = '';
    public $minPrice = '';
    public $maxPrice = '';
    public $selectedCategory = '';
    public $sortBy = 'popular';
    public $priceRange = [0, 10000000]; // Default range 0 - 10 juta untuk servis

    public $categories;

    protected $queryString = [
        'search' => ['except' => ''],
        'minPrice' => ['except' => ''],
        'maxPrice' => ['except' => ''],
        'selectedCategory' => ['except' => ''],
        'sortBy' => ['except' => 'popular'],
    ];

    public function mount()
    {
        // Ambil semua kategori yang memiliki servis aktif
        $this->categories = Category::whereHas('services', function ($query) {
            $query->where('is_active', true);
        })
            ->get();

        // Set rentang harga berdasarkan harga servis aktual
        $priceStats = Service::where('is_active', true)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        if ($priceStats && $priceStats->min_price !== null && $priceStats->max_price !== null) {
            $this->priceRange = [
                (int) $priceStats->min_price,
                (int) $priceStats->max_price
            ];
        }
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

    public function bookService($serviceId)
    {
        // Cek apakah customer sudah login
        if (!auth()->guard('customer')->check()) {
            session()->flash('auth-message', 'Silakan login terlebih dahulu untuk memesan layanan.');
            return;
        }

        // Logic untuk booking servis - redirect ke halaman pemesanan
        // Untuk saat ini, karena halaman order belum ada, tampilkan pesan
        session()->flash('service-message', 'Fitur pemesanan akan segera tersedia. Silakan hubungi kami langsung untuk memesan layanan ini.');

        // Emit event untuk proses pemesanan (bisa digunakan nanti)
        $this->dispatch('service-booked', $serviceId);

        // TODO: Redirect ke halaman order service ketika sudah dibuat
        // return redirect()->route('order-service.create', ['service_id' => $serviceId]);
    }

    public function render()
    {
        $query = Service::with(['category'])
            ->where('is_active', true);

        // Terapkan filter pencarian
        if (!empty($this->search)) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        // Terapkan filter kategori
        if (!empty($this->selectedCategory)) {
            $query->where('categories_id', $this->selectedCategory);
        }

        // Terapkan filter harga
        if (!empty($this->minPrice) && is_numeric($this->minPrice)) {
            $query->where('price', '>=', $this->minPrice);
        }

        if (!empty($this->maxPrice) && is_numeric($this->maxPrice)) {
            $query->where('price', '<=', $this->maxPrice);
        }

        // Terapkan pengurutan
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

        $services = $query->paginate(12);

        return view('livewire.public.service-page', [
            'services' => $services
        ]);
    }
}
