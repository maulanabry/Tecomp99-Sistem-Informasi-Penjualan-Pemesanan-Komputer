<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Service;

class ServiceOverview extends Component
{
    public $service;
    public $showLoginAlert = false;

    public function mount(Service $service)
    {
        $this->service = $service;

        // Ensure login alert is initially hidden
        $this->showLoginAlert = false;
    }

    /**
     * Check if customer is authenticated
     */
    private function isCustomerAuthenticated()
    {
        return auth()->guard('customer')->check();
    }

    public function bookService()
    {
        // Cek apakah customer sudah login
        if (!$this->isCustomerAuthenticated()) {
            $this->showLoginAlert = true;
            return;
        }

        // Logic untuk memesan servis
        // Untuk saat ini, redirect ke halaman pemesanan servis
        session()->flash('success-message', 'Mengarahkan ke halaman pemesanan...');

        // Emit event untuk proses pemesanan
        $this->dispatch('service-booking-clicked', [
            'service_id' => $this->service->service_id
        ]);

        // Redirect ke halaman pemesanan servis (akan dibuat nanti)
        // return redirect()->route('order-service.create', ['service_id' => $this->service->service_id]);
    }

    public function closeLoginAlert()
    {
        $this->showLoginAlert = false;
    }

    public function render()
    {
        return view('livewire.customer.service-overview');
    }
}
