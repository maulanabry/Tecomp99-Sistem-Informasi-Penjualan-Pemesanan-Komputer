<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Brand;
use Exception;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class BrandSummaryCards extends Component
{
    public $totalBrand;
    public $brandTerbaru;
    public $brandTerhapus;

    public function mount()
    {
        $this->refreshCounts();
    }

    public function refreshCounts()
    {
        try {
            $this->totalBrand = Brand::count();
            $this->brandTerbaru = Brand::where('created_at', '>=', Carbon::now()->subDays(7))->count();
            $this->brandTerhapus = Brand::onlyTrashed()->count();
        } catch (Exception $e) {
            Log::error('Error fetching brand counts: ' . $e->getMessage());
            $this->totalBrand = 0;
            $this->brandTerbaru = 0;
            $this->brandTerhapus = 0;
        }
    }

    public function render()
    {
        return view('livewire.admin.brand-summary-cards');
    }
}
