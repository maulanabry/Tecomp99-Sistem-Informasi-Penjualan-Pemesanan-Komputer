<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\OrderProduct;
use App\Models\OrderService;
use App\Models\ServiceTicket;

class OwnerDashboardOperationalTabs extends Component
{
    protected $listeners = ['refresh-dashboard' => '$refresh'];

    public $activeTab = 'jadwal-servis';

    public function mount()
    {
        $this->dispatch('refresh-dashboard');
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->dispatch('refresh-dashboard');
    }

    public function render()
    {
        return view('livewire.owner.owner-dashboard-operational-tabs');
    }
}
