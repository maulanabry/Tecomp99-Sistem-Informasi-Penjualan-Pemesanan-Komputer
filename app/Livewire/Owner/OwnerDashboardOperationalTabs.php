<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\OrderProduct;
use App\Models\OrderService;
use App\Models\ServiceTicket;

class OwnerDashboardOperationalTabs extends Component
{
    public $activeTab = 'jadwal-servis';

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.owner.owner-dashboard-operational-tabs');
    }
}
