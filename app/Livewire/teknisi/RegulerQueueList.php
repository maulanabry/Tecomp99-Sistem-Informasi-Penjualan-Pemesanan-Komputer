<?php

namespace App\Livewire\Teknisi;

use Livewire\Component;
use App\Models\ServiceTicket;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class RegulerQueueList extends Component
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
        $query = ServiceTicket::with(['orderService.customer', 'admin'])
            ->whereHas('orderService', function ($query) {
                $query->where('type', 'reguler');

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
            ->where('admin_id', Auth::id());

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        // Apply time filter
        switch ($this->timeFilter) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                break;
        }

        $tickets = $query->orderBy('created_at', 'asc')->get();

        // Group tickets by date and assign queue numbers
        $queueByDate = [];
        $queueNumber = 1;

        foreach ($tickets as $ticket) {
            $date = $ticket->created_at->format('Y-m-d');

            if (!isset($queueByDate[$date])) {
                $queueByDate[$date] = [
                    'date' => $date,
                    'services' => []
                ];
            }

            $queueByDate[$date]['services'][] = [
                'ticket' => $ticket,
                'queue_number' => $queueNumber++
            ];
        }

        return view('livewire.teknisi.reguler-queue-list', [
            'queueByDate' => $queueByDate
        ]);
    }
}
