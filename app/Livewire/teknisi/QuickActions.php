<?php

namespace App\Livewire\teknisi;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class QuickActions extends Component
{
    /**
     * Navigate to create service ticket
     */
    public function createServiceTicket()
    {
        return redirect()->route('teknisi.service-tickets.create');
    }

    /**
     * Navigate to search order services
     */
    public function searchOrderServices()
    {
        return redirect()->route('teknisi.order-services.index');
    }

    /**
     * Navigate to weekly schedule
     */
    public function viewWeeklySchedule()
    {
        return redirect()->route('teknisi.jadwal-servis.index');
    }

    /**
     * Navigate to profile settings
     */
    public function profileSettings()
    {
        return redirect()->route('teknisi.settings.index');
    }

    /**
     * Navigate to service tickets
     */
    public function viewServiceTickets()
    {
        return redirect()->route('teknisi.service-tickets.index');
    }

    /**
     * Navigate to notifications
     */
    public function viewNotifications()
    {
        return redirect()->route('teknisi.notifications.index');
    }

    public function render()
    {
        return view('livewire.teknisi.quick-actions');
    }
}
