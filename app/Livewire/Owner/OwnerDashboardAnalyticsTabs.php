<?php

namespace App\Livewire\Owner;

use Livewire\Component;

class OwnerDashboardAnalyticsTabs extends Component
{
    protected $listeners = ['refresh-dashboard' => '$refresh'];

    public $activeTab = 'tren-pendapatan';

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
        return view('livewire.owner.owner-dashboard-analytics-tabs');
    }
}
