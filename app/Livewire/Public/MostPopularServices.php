<?php

namespace App\Livewire\Public;

use App\Models\Service;
use Livewire\Component;

class MostPopularServices extends Component
{
    public $services;

    public function mount()
    {
        // Ambil layanan terpopuler berdasarkan sold_count
        $this->services = Service::with('category')
            ->where('is_active', true)
            ->orderBy('sold_count', 'desc')
            ->limit(6)
            ->get();
    }

    public function render()
    {
        return view('livewire.public.most-popular-services');
    }
}
