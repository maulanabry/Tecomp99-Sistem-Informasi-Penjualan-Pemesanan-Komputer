<?php

namespace App\Livewire\Teknisi;

use Livewire\Component;
use App\Models\ServiceTicket;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class VisitScheduleList extends Component
{
    public $search = '';
    public $statusFilter = '';
    public $timeFilter = 'today';

    public function updatedSearch()
    {
        // Livewire will automatically re-render when this property changes
    }

    public function updatedStatusFilter()
    {
        // Livewire will automatically re-render when this property changes
    }

    public function updatedTimeFilter()
    {
        // Livewire will automatically re-render when this property changes
    }

    public function render()
    {
        $query = ServiceTicket::with(['orderService.customer.addresses', 'admin'])
            ->whereHas('orderService', function ($query) {
                $query->where('type', 'onsite');

                if ($this->search) {
                    $query->where(function ($q) {
                        $q->where('service_ticket_id', 'like', '%' . $this->search . '%')
                            ->orWhereHas('customer', function ($q) {
                                $q->where('name', 'like', '%' . $this->search . '%');
                            })
                            ->orWhere('device', 'like', '%' . $this->search . '%');
                    });
                }
            })
            ->where('admin_id', Auth::id())
            ->whereNotNull('visit_schedule');

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Apply time filter based on visit_schedule
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

        $visitSchedules = $query->orderBy('visit_schedule', 'asc')->get();

        return view('livewire.teknisi.visit-schedule-list', [
            'visitSchedules' => $visitSchedules
        ]);
    }
}
