<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Promo;
use Exception;
use Illuminate\Support\Facades\Log;

class PromoSummaryCards extends Component
{
    public $totalPromo;
    public $promoAktif;
    public $promoTidakAktif;
    public $promoTerhapus;
    public $showAll = false;

    public function mount()
    {
        $this->refreshCounts();
    }

    public function refreshCounts()
    {
        try {
            $this->totalPromo = Promo::count();
            $this->promoAktif = Promo::where('is_active', true)->count();
            $this->promoTidakAktif = Promo::where('is_active', false)->count();
            $this->promoTerhapus = Promo::onlyTrashed()->count();
        } catch (Exception $e) {
            Log::error('Error fetching promo counts: ' . $e->getMessage());
            $this->totalPromo = 0;
            $this->promoAktif = 0;
            $this->promoTidakAktif = 0;
            $this->promoTerhapus = 0;
        }
    }

    public function toggleShowAll()
    {
        $this->showAll = !$this->showAll;
    }

    public function render()
    {
        return view('livewire.admin.promo-summary-cards');
    }
}
