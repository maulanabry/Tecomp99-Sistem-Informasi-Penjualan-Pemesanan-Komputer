<?php

namespace App\Livewire\Teknisi;

use App\Models\ServiceTicket;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TeknisiCalendar extends Component
{
    public $currentDate;
    public $selectedDate;
    public $viewMode = 'month'; // month, week

    public function mount()
    {
        $this->currentDate = Carbon::now();
        $this->selectedDate = Carbon::now();
    }

    public function previousMonth()
    {
        $this->currentDate = $this->currentDate->subMonth();
    }

    public function nextMonth()
    {
        $this->currentDate = $this->currentDate->addMonth();
    }

    public function selectDate($date)
    {
        $this->selectedDate = Carbon::parse($date);
    }

    public function toggleViewMode()
    {
        $this->viewMode = $this->viewMode === 'month' ? 'week' : 'month';
    }

    public function getCalendarData()
    {
        $teknisiId = Auth::guard('teknisi')->id();
        $startOfMonth = $this->currentDate->copy()->startOfMonth();
        $endOfMonth = $this->currentDate->copy()->endOfMonth();

        // Get all service tickets for this month
        $tickets = ServiceTicket::where('admin_id', $teknisiId)
            ->whereBetween('visit_schedule', [$startOfMonth, $endOfMonth])
            ->with(['orderService.customer'])
            ->get()
            ->groupBy(function ($ticket) {
                return Carbon::parse($ticket->visit_schedule)->format('Y-m-d');
            });

        // Generate calendar grid
        $calendar = [];
        $startOfCalendar = $startOfMonth->copy()->startOfWeek();
        $endOfCalendar = $endOfMonth->copy()->endOfWeek();

        $current = $startOfCalendar->copy();
        while ($current <= $endOfCalendar) {
            $dateKey = $current->format('Y-m-d');
            $dayTickets = $tickets->get($dateKey, collect());

            $calendar[] = [
                'date' => $current->copy(),
                'is_current_month' => $current->month === $this->currentDate->month,
                'is_today' => $current->isToday(),
                'is_selected' => $current->isSameDay($this->selectedDate),
                'tickets_count' => $dayTickets->count(),
                'tickets' => $dayTickets->map(function ($ticket) {
                    $orderService = $ticket->orderService;
                    $customer = $orderService ? $orderService->customer : null;

                    return [
                        'id' => $ticket->service_ticket_id,
                        'time' => Carbon::parse($ticket->visit_schedule)->format('H:i'),
                        'customer' => $customer ? $customer->name : 'Unknown',
                        'status' => $ticket->status,
                        'type' => $orderService ? $orderService->type : 'reguler'
                    ];
                })
            ];
            $current->addDay();
        }

        return collect($calendar)->chunk(7); // Group by weeks
    }

    public function getSelectedDateTickets()
    {
        $teknisiId = Auth::guard('teknisi')->id();

        return ServiceTicket::where('admin_id', $teknisiId)
            ->whereDate('visit_schedule', $this->selectedDate)
            ->with(['orderService.customer', 'orderService.orderServiceItems.service'])
            ->orderBy('visit_schedule')
            ->get()
            ->map(function ($ticket) {
                $orderService = $ticket->orderService;
                $customer = $orderService->customer ?? null;
                $serviceItems = $orderService->orderServiceItems ?? collect();
                $firstServiceItem = $serviceItems->first();
                $service = $firstServiceItem ? $firstServiceItem->service : null;

                return [
                    'id' => $ticket->service_ticket_id,
                    'time' => Carbon::parse($ticket->visit_schedule)->format('H:i'),
                    'customer' => $customer ? $customer->name : 'Unknown',
                    'service' => $service ? $service->name : 'Service',
                    'status' => $ticket->status,
                    'type' => $orderService->type ?? 'reguler',
                    'address' => ($orderService->type === 'onsite' && $customer) ?
                        ($customer->customerAddresses->first()->address ?? '') : null
                ];
            });
    }

    public function render()
    {
        return view('livewire.teknisi.teknisi-calendar', [
            'calendarWeeks' => $this->getCalendarData(),
            'selectedDateTickets' => $this->getSelectedDateTickets(),
            'monthName' => $this->currentDate->format('F Y'),
            'selectedDateFormatted' => $this->selectedDate->format('d F Y')
        ]);
    }
}
