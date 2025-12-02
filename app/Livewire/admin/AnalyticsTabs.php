<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class AnalyticsTabs extends Component
{
    public $activeTab = 'revenue';

    protected $listeners = ['refresh-dashboard' => '$refresh'];

    public function mount()
    {
        $this->activeTab = request('tab', 'revenue');
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->dispatch('refresh-charts');
    }

    public function render()
    {
        return view('livewire.admin.analytics-tabs');
    }
}
