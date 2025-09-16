<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class AnalyticsTabs extends Component
{
    public $activeTab = 'revenue';

    protected $listeners = ['refresh-dashboard' => '$refresh'];

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.analytics-tabs');
    }
}
