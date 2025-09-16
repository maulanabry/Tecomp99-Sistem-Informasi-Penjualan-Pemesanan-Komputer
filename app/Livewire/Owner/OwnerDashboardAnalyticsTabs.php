<?php

namespace App\Livewire\Owner;

use Livewire\Component;

class OwnerDashboardAnalyticsTabs extends Component
{
    public $activeTab = 'tren-pendapatan';

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.owner.owner-dashboard-analytics-tabs');
    }
}
