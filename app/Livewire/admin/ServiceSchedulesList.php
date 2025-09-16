<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\OrderService;
use Carbon\Carbon;

class ServiceSchedulesList extends Component
{
    public $schedules = [];

    protected $listeners = ['refresh-dashboard' => 'loadData'];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        // Tab 2: Jadwal Servis (semua order servis, urut tanggal terdekat)
        $this->schedules = OrderService::with(['customer', 'tickets'])
            ->whereNotIn('status_order', ['selesai', 'dibatalkan'])
            ->orderBy('created_at', 'asc')
            ->take(5)
            ->get();
    }

    public function scheduleService($orderServiceId)
    {
        // Logic to schedule service
        session()->flash('schedule_message', 'Servis berhasil dijadwalkan');
        session()->flash('schedule_type', 'success');
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.admin.service-schedules-list');
    }
}
