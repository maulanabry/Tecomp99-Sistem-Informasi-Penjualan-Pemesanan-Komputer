<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Service;

class ServiceCard extends Component
{
    public Service $service;
    public $loading = false;

    public function addToOrder()
    {
        $this->loading = true;
        $this->dispatch('serviceSelected', serviceId: $this->service->service_id);
        $this->loading = false;
    }

    public function render()
    {
        return view('livewire.admin.service-card', [
            'formattedPrice' => 'Rp ' . number_format($this->service->price, 0, ',', '.'),
        ]);
    }
}
