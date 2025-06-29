<?php

namespace App\Livewire\admin;

use Livewire\Component;
use App\Models\ServiceTicket;
use App\Models\Admin;
use Livewire\WithPagination;
use Carbon\Carbon;

class VisitScheduleList extends Component
{
    use WithPagination;

    public $search = '';
    public $timeFilter = 'today'; // today, week, month
    public $teknisiFilter = ''; // Filter by teknisi

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = ServiceTicket::query()
            ->with(['orderService.customer.defaultAddress', 'orderService.customer.addresses', 'admin'])
            ->whereNotNull('visit_schedule')
            ->whereHas('orderService', function ($q) {
                $q->where('type', 'onsite');
            })
            ->whereNotIn('status', ['Selesai', 'Dibatalkan']);

        // Apply time filter
        switch ($this->timeFilter) {
            case 'today':
                $query->whereDate('visit_schedule', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('visit_schedule', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('visit_schedule', Carbon::now()->month)
                    ->whereYear('visit_schedule', Carbon::now()->year);
                break;
        }

        // Apply teknisi filter
        if ($this->teknisiFilter) {
            $query->where('admin_id', $this->teknisiFilter);
        }

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('service_ticket_id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('orderService.customer', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('orderService', function ($q) {
                        $q->where('device', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $visitSchedules = $query->orderBy('visit_schedule', 'asc')->get();

        // Get list of teknisi for filter dropdown
        $teknisiList = Admin::where('role', 'teknisi')->get();

        return view('livewire.admin.visit-schedule-list', [
            'visitSchedules' => $visitSchedules,
            'teknisiList' => $teknisiList
        ]);
    }
}
