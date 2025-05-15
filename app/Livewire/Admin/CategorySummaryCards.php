<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Log;

class CategorySummaryCards extends Component
{
    public $totalKategori;
    public $kategoriProduk;
    public $kategoriLayanan;
    public $kategoriTerhapus;

    public function mount()
    {
        $this->refreshCounts();
    }

    public function refreshCounts()
    {
        try {
            $this->totalKategori = Category::count();
            $this->kategoriProduk = Category::where('type', 'produk')->count();
            $this->kategoriLayanan = Category::where('type', 'layanan')->count();
            $this->kategoriTerhapus = Category::onlyTrashed()->count();
        } catch (Exception $e) {
            Log::error('Error fetching category counts: ' . $e->getMessage());
            // Fallback to default values to avoid UI errors
            $this->totalKategori = 0;
            $this->kategoriProduk = 0;
            $this->kategoriLayanan = 0;
            $this->kategoriTerhapus = 0;
        }
    }

    public function render()
    {
        return view('livewire.admin.category-summary-cards');
    }
}
