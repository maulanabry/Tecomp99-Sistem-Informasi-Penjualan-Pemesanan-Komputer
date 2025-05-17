<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Topbar extends Component
{
    public function toggleSidebar()
    {
        $this->dispatch('toggleSidebar');
    }

    public function render()
    {
        return view('livewire.admin.topbar');
    }
}
