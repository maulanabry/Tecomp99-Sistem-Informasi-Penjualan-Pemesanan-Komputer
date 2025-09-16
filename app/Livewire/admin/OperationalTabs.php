<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class OperationalTabs extends Component
{
    public $activeTab = 'product-orders';

    protected $listeners = ['refresh-dashboard' => '$refresh'];

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.admin.operational-tabs');
    }
}
