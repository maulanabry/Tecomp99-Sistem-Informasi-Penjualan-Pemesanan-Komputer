<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Service;
use Exception;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ServiceSummaryCards extends Component
{
    public $totalServis;
    public $servisPopuler;
    public $servisKurangDiminati;
    public $servisTerhapus;
    public $servisAktif;
    public $servisTidakAktif;
    public $servisTanpaPenjualan;
    public $servisTerakhirDiperbarui;
    public $servisTotalPemesanan;
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
            $this->totalServis = Service::count();
            $this->servisPopuler = Service::orderByDesc('sold_count')->value('name') ?? '-';
            $this->servisKurangDiminati = Service::orderBy('sold_count')->value('name') ?? '-';
            $this->servisTerhapus = Service::onlyTrashed()->count();
            $this->servisAktif = Service::where('is_active', true)->count();
            $this->servisTidakAktif = Service::where('is_active', false)->count();
            $this->servisTanpaPenjualan = Service::where('sold_count', 0)->count();
            $this->servisTerakhirDiperbarui = Service::orderByDesc('updated_at')->value('name') ?? '-';
            $this->servisTotalPemesanan = Service::sum('sold_count');
        } catch (Exception $e) {
            Log::error('Error fetching service counts: ' . $e->getMessage());
            $this->totalServis = 0;
            $this->servisPopuler = '-';
            $this->servisKurangDiminati = '-';
            $this->servisTerhapus = 0;
            $this->servisAktif = 0;
            $this->servisTidakAktif = 0;
            $this->servisTanpaPenjualan = 0;
            $this->servisTerakhirDiperbarui = '-';
            $this->servisTotalPemesanan = 0;
        }
    }

    public function render()
    {
        return view('livewire.admin.service-summary-cards');
    }
}
