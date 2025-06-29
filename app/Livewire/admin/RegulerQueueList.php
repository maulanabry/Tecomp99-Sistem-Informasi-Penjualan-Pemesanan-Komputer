<?php

namespace App\Livewire\Admin;

use App\Models\OrderService;
use App\Models\Admin;
use Livewire\Component;
use Carbon\Carbon;

class RegulerQueueList extends Component
{
    public $search = '';
    public $timeFilter = 'today'; // today, week, month
    public $statusFilter = ''; // all, menunggu, diproses
    public $teknisiFilter = ''; // Filter by teknisi

    public function render()
    {
        // Get all active reguler service tickets sorted by FIFO
        $query = \App\Models\ServiceTicket::with(['orderService.customer', 'admin'])
            ->whereHas('orderService', function ($q) {
                $q->where('type', 'reguler');
            })
            ->whereIn('status', ['Menunggu', 'Diproses'])
            ->orderBy('created_at', 'asc');

        // Apply status filter (based on ticket status)
        if ($this->statusFilter) {
            $query->where('status', ucfirst($this->statusFilter));
        }

        // Apply teknisi filter
        if ($this->teknisiFilter) {
            $query->where('admin_id', $this->teknisiFilter);
        }

        // Apply search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('service_ticket_id', 'like', '%' . $this->search . '%')
                    ->orWhere('order_service_id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('orderService.customer', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('orderService', function ($q) {
                        $q->where('device', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $regulerTickets = $query->get();

        // Group tickets by scheduled days (FIFO distribution)
        $queueByDate = [];
        $maxPerDay = 8;
        $currentDate = Carbon::today();
        $ticketIndex = 0;

        // Distribute all active reguler tickets starting from today (prioritize all pending tickets)
        // This ensures all pending tickets are shown starting from today regardless of creation date
        foreach ($regulerTickets as $ticket) {
            $dayOffset = intval($ticketIndex / $maxPerDay);
            $assignDate = $currentDate->copy()->addDays($dayOffset);
            $dateKey = $assignDate->toDateString();

            // Apply time filter to the assigned date
            $includeTicket = true;
            switch ($this->timeFilter) {
                case 'today':
                    $includeTicket = $assignDate->isToday();
                    break;
                case 'week':
                    $includeTicket = $assignDate->between(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
                    break;
                case 'month':
                    $includeTicket = $assignDate->month === Carbon::now()->month && $assignDate->year === Carbon::now()->year;
                    break;
            }

            if ($includeTicket) {
                if (!isset($queueByDate[$dateKey])) {
                    $queueByDate[$dateKey] = [
                        'date' => $assignDate,
                        'services' => []
                    ];
                }

                $queueByDate[$dateKey]['services'][] = [
                    'ticket' => $ticket,
                    'queue_number' => ($ticketIndex % $maxPerDay) + 1
                ];
            }
            $ticketIndex++;
        }

        // Get list of teknisi for filter dropdown
        $teknisiList = Admin::where('role', 'teknisi')->get();

        return view('livewire.admin.reguler-queue-list', [
            'queueByDate' => $queueByDate,
            'totalServices' => $regulerTickets->count(),
            'teknisiList' => $teknisiList
        ]);
    }
}
