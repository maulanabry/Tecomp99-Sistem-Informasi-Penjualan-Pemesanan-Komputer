<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\Log;

class CustomerSummaryCards extends Component
{
    public $totalCustomers;
    public $CustomersAktif;
    public $CustomersTidakAktif;
    public $CustomersTerhapus;

    public function mount()
    {
        $this->refreshCounts();
    }

    public function refreshCounts()
    {
        try {
            $this->totalCustomers = Customer::count();
            $this->CustomersAktif = Customer::where('hasAccount', true)->count();
            $this->CustomersTidakAktif = Customer::where('hasAccount', false)->count();
            $this->CustomersTerhapus = Customer::onlyTrashed()->count();
        } catch (Exception $e) {
            Log::error('Error fetching customer counts: ' . $e->getMessage());
            $this->totalCustomers = 0;
            $this->CustomersAktif = 0;
            $this->CustomersTidakAktif = 0;
            $this->CustomersTerhapus = 0;
        }
    }

    public function render()
    {
        return view('livewire.admin.customer-summary-cards');
    }
}
