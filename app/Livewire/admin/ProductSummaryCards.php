<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;
use Exception;
use Illuminate\Support\Facades\Log;

class ProductSummaryCards extends Component
{
    public $totalProduk;
    public $produkPopuler;
    public $stokHabis;
    public $produkTerhapus;
    public $produkKurangDiminati;
    public $produkAktif;
    public $produkTidakAktif;
    public $produkTerakhirDiperbarui;
    public $produkTotalPenjualan;
    public $showAllCards = false;

    public function mount()
    {
        $this->refreshCounts();
    }

    public function toggleCards()
    {
        $this->showAllCards = !$this->showAllCards;
    }

    public function refreshCounts()
    {
        try {
            $this->totalProduk = Product::count();
            $this->produkPopuler = Product::orderBy('sold_count', 'desc')->limit(1)->value('name') ?? '-';
            $this->stokHabis = Product::where('stock', '<=', 0)->count();
            $this->produkTerhapus = Product::onlyTrashed()->count();
            $this->produkKurangDiminati = Product::orderBy('sold_count', 'asc')->limit(1)->value('name') ?? '-';
            $this->produkAktif = Product::where('is_active', true)->count();
            $this->produkTidakAktif = Product::where('is_active', false)->count();
            $this->produkTerakhirDiperbarui = Product::orderBy('updated_at', 'desc')->limit(1)->value('name') ?? '-';
            $this->produkTotalPenjualan = Product::sum('sold_count');
        } catch (Exception $e) {
            Log::error('Error fetching product counts: ' . $e->getMessage());
            $this->totalProduk = 0;
            $this->produkPopuler = '-';
            $this->stokHabis = 0;
            $this->produkTerhapus = 0;
            $this->produkKurangDiminati = '-';
            $this->produkAktif = 0;
            $this->produkTidakAktif = 0;
            $this->produkTerakhirDiperbarui = '-';
            $this->produkTotalPenjualan = 0;
        }
    }

    public function render()
    {
        return view('livewire.admin.product-summary-cards');
    }
}
