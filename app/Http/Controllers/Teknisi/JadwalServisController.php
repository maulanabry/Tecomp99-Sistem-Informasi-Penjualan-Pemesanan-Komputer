<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\ServiceTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalServisController extends Controller
{
    public function index()
    {
        return view('teknisi.jadwal-servis.index');
    }

    public function calendar()
    {
        $tickets = ServiceTicket::with(['orderService.customer.addresses', 'orderService.customer.defaultAddress'])
            ->where('admin_id', Auth::guard('teknisi')->id()) // Only show tickets assigned to current teknisi
            ->whereHas('orderService', function ($query) {
                $query->whereNotNull('type');
            })
            ->get()
            ->map(function ($ticket) {
                $events = [];

                // Get customer address
                $customerAddress = $ticket->orderService->customer->defaultAddress ?? $ticket->orderService->customer->addresses->first();
                $formattedAddress = 'No address';
                if ($customerAddress) {
                    $formattedAddress = $customerAddress->detail_address . ', ' .
                        $customerAddress->district_name . ', ' .
                        $customerAddress->city_name;
                }

                // Add service duration event with enhanced styling
                if ($ticket->schedule_date && $ticket->estimate_date) {
                    $events[] = [
                        'id' => 'duration_' . $ticket->id,
                        'title' => "Service #" . $ticket->service_ticket_id,
                        'start' => $ticket->schedule_date,
                        'end' => $ticket->estimate_date,
                        'backgroundColor' => '#3788d8',
                        'borderColor' => '#3788d8',
                        'textColor' => '#ffffff',
                        'classNames' => ['service-duration'],
                        'extendedProps' => [
                            'ticket_id' => $ticket->service_ticket_id,
                            'customer_name' => $ticket->orderService->customer->name,
                            'type' => $ticket->orderService->type,
                            'device' => $ticket->orderService->device,
                            'status' => $ticket->status,
                            'eventType' => 'duration',
                            'address' => $formattedAddress
                        ]
                    ];
                }

                // Add visit schedule event for onsite services with enhanced styling
                if ($ticket->orderService->type === 'onsite' && $ticket->visit_schedule) {
                    $events[] = [
                        'id' => 'visit_' . $ticket->id,
                        'title' => "Visit #" . $ticket->service_ticket_id,
                        'start' => $ticket->visit_schedule,
                        'end' => \Carbon\Carbon::parse($ticket->visit_schedule)->addHour(),
                        'backgroundColor' => '#dc3545',
                        'borderColor' => '#dc3545',
                        'textColor' => '#ffffff',
                        'classNames' => ['visit-schedule'],
                        'display' => 'block',
                        'extendedProps' => [
                            'ticket_id' => $ticket->service_ticket_id,
                            'customer_name' => $ticket->orderService->customer->name,
                            'type' => $ticket->orderService->type,
                            'device' => $ticket->orderService->device,
                            'status' => $ticket->status,
                            'eventType' => 'visit',
                            'address' => $formattedAddress
                        ]
                    ];
                }

                return $events;
            })
            ->flatten(1)
            ->values();

        return view('teknisi.jadwal-servis.calendar', compact('tickets'));
    }

    public function calendarEvents()
    {
        $serviceTickets = ServiceTicket::with(['orderService.customer.addresses', 'orderService.customer.defaultAddress'])
            ->where('admin_id', Auth::guard('teknisi')->id()) // Only show tickets assigned to current teknisi
            ->whereHas('orderService', function ($query) {
                $query->whereNotNull('type');
            })
            ->get();

        $events = [];

        // Existing events for onsite visits only
        foreach ($serviceTickets as $ticket) {
            // Get customer address
            $customerAddress = $ticket->orderService->customer->defaultAddress ?? $ticket->orderService->customer->addresses->first();
            $formattedAddress = 'No address';
            if ($customerAddress) {
                $formattedAddress = $customerAddress->detail_address . ', ' .
                    $customerAddress->district_name . ', ' .
                    $customerAddress->city_name;
            }

            // Add visit schedule event for onsite services with enhanced styling
            if ($ticket->orderService->type === 'onsite' && $ticket->visit_schedule) {
                $events[] = [
                    'id' => 'visit_' . $ticket->id,
                    'title' => "Visit #" . $ticket->service_ticket_id,
                    'start' => $ticket->visit_schedule,
                    'end' => \Carbon\Carbon::parse($ticket->visit_schedule)->addHour(),
                    'backgroundColor' => '#dc3545',
                    'borderColor' => '#dc3545',
                    'textColor' => '#ffffff',
                    'classNames' => ['visit-schedule'],
                    'display' => 'block',
                    'extendedProps' => [
                        'ticket_id' => $ticket->service_ticket_id,
                        'customer_name' => $ticket->orderService->customer->name,
                        'type' => $ticket->orderService->type,
                        'device' => $ticket->orderService->device,
                        'status' => $ticket->status,
                        'eventType' => 'visit',
                        'address' => $formattedAddress
                    ]
                ];
            }
        }

        // New logic for reguler queue events with FIFO scheduling from today
        $regulerTickets = ServiceTicket::with(['orderService.customer.addresses', 'orderService.customer.defaultAddress'])
            ->where('admin_id', Auth::guard('teknisi')->id()) // Only show tickets assigned to current teknisi
            ->whereHas('orderService', function ($q) {
                $q->where('type', 'reguler');
            })
            ->whereIn('status', ['Menunggu', 'Diproses'])
            ->orderBy('created_at', 'asc')
            ->get();

        // FIFO-based distribution starting from today (prioritize all pending tickets)
        $queueByDate = [];
        $maxPerDay = 8;
        $currentDate = \Carbon\Carbon::today();
        $ticketIndex = 0;

        // Distribute all active reguler tickets starting from today regardless of creation date
        // This ensures all pending tickets are prioritized and shown starting from today
        foreach ($regulerTickets as $ticket) {
            // Calculate which day this ticket should be assigned to (starting from today)
            $dayOffset = intval($ticketIndex / $maxPerDay);
            $assignDate = $currentDate->copy()->addDays($dayOffset)->toDateString();

            // Initialize array if not exists
            if (!isset($queueByDate[$assignDate])) {
                $queueByDate[$assignDate] = [];
            }

            // Add ticket to the queue for the assigned date
            $queueByDate[$assignDate][] = $ticket;
            $ticketIndex++;
        }

        // Generate events for reguler queue
        foreach ($queueByDate as $date => $tickets) {
            foreach ($tickets as $index => $ticket) {
                // Get customer address for reguler queue
                $customerAddress = $ticket->orderService->customer->defaultAddress ?? $ticket->orderService->customer->addresses->first();
                $formattedAddress = 'No address';
                if ($customerAddress) {
                    $formattedAddress = $customerAddress->detail_address . ', ' .
                        $customerAddress->district_name . ', ' .
                        $customerAddress->city_name;
                }

                $queueNumber = $index + 1;
                $events[] = [
                    'id' => 'reguler_' . $ticket->service_ticket_id,
                    'title' => "Antrian #{$queueNumber} - " . $ticket->orderService->customer->name,
                    'start' => $date,
                    'allDay' => true,
                    'backgroundColor' => '#f59e0b', // amber-500
                    'borderColor' => '#f59e0b',
                    'textColor' => '#000000',
                    'classNames' => ['reguler-queue'],
                    'extendedProps' => [
                        'ticket_id' => $ticket->service_ticket_id,
                        'customer_name' => $ticket->orderService->customer->name,
                        'type' => 'reguler',
                        'device' => $ticket->orderService->device ?? '',
                        'status' => $ticket->status,
                        'eventType' => 'reguler',
                        'order_service_id' => $ticket->order_service_id,
                        'created_at' => $ticket->created_at->format('Y-m-d H:i:s'),
                        'address' => $formattedAddress
                    ]
                ];
            }
        }

        return response()->json($events);
    }
}
