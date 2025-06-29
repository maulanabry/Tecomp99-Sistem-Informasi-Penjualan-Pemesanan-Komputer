<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceTicket;
use App\Models\ServiceAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceTicketController extends Controller
{
    public function index()
    {
        return view('admin.service-ticket');
    }

    public function calendar()
    {
        $tickets = ServiceTicket::with(['orderService.customer'])
            ->whereHas('orderService', function ($query) {
                $query->whereNotNull('type');
            })
            ->get()
            ->map(function ($ticket) {
                $events = [];

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
                            'eventType' => 'duration'
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
                            'address' => $ticket->orderService->customer->addresses->first()?->address ?? 'No address'
                        ]
                    ];
                }

                return $events;
            })
            ->flatten(1)
            ->values();

        return view('admin.service-ticket.calendar', compact('tickets'));
    }


    public function calendarEvents()
    {
        $serviceTickets = ServiceTicket::with(['orderService.customer'])
            ->whereHas('orderService', function ($query) {
                $query->whereNotNull('type');
            })
            ->get();

        $events = [];

        // Existing events for onsite visits only
        foreach ($serviceTickets as $ticket) {
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
                        'address' => $ticket->orderService->customer->addresses->first()?->address ?? 'No address'
                    ]
                ];
            }
        }

        // New logic for reguler queue events with FIFO scheduling from today
        $regulerTickets = ServiceTicket::with(['orderService.customer'])
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
                        'created_at' => $ticket->created_at->format('Y-m-d H:i:s')
                    ]
                ];
            }
        }

        return response()->json($events);
    }

    public function create()
    {
        $orderServices = \App\Models\OrderService::where('hasTicket', false)
            ->with('customer')
            ->get();

        $technicians = \App\Models\Admin::where('role', 'teknisi')->get();

        return view('admin.service-ticket.create', compact('orderServices', 'technicians'));
    }

    public function store(Request $request)
    {
        // Get the order service to check its type
        $orderService = \App\Models\OrderService::find($request->order_service_id);

        $rules = [
            'order_service_id' => 'required|exists:order_services,order_service_id',
            'admin_id' => 'required|exists:admins,id',
            'schedule_date' => 'required|date|after_or_equal:today',
            'estimation_days' => 'nullable|integer|min:1',
        ];

        // Add visit schedule validation only for onsite orders
        if ($orderService && $orderService->type === 'onsite') {
            $rules['visit_date'] = 'required|date';
            $rules['visit_time_slot'] = 'required|in:08:00,09:30,11:00,13:00,14:30,16:00';
        }

        $validated = $request->validate($rules);

        // Handle visit schedule for onsite orders
        if ($orderService && $orderService->type === 'onsite') {
            // Check slot availability
            $isSlotAvailable = $this->checkSlotAvailabilityInternal(
                $validated['admin_id'],
                $validated['visit_date'],
                $validated['visit_time_slot']
            );

            if (!$isSlotAvailable['available']) {
                return back()->withErrors(['visit_time_slot' => $isSlotAvailable['message']])->withInput();
            }

            $validated['visit_schedule'] = $validated['visit_date'] . ' ' . $validated['visit_time_slot'] . ':00';
            unset($validated['visit_date'], $validated['visit_time_slot']);
        }

        // Calculate estimate_date if estimation_days is provided
        if (!empty($validated['estimation_days'])) {
            $scheduleDate = new \DateTime($validated['schedule_date']);
            $validated['estimate_date'] = $scheduleDate->modify("+{$validated['estimation_days']} days")->format('Y-m-d');
        }

        // Generate sequential number for today
        $today = date('dmy');
        $lastTicket = ServiceTicket::where('service_ticket_id', 'like', "TKT{$today}%")
            ->orderBy('service_ticket_id', 'desc')
            ->first();

        $sequence = '001';
        if ($lastTicket) {
            $lastSequence = substr($lastTicket->service_ticket_id, -3);
            $sequence = str_pad((int)$lastSequence + 1, 3, '0', STR_PAD_LEFT);
        }

        $validated['service_ticket_id'] = "TKT{$today}{$sequence}";
        $validated['status'] = 'Menunggu';

        // Begin transaction
        DB::beginTransaction();
        try {
            // Create service ticket
            $ticket = ServiceTicket::create($validated);

            // Generate action ID with same date format
            $lastAction = ServiceAction::where('service_action_id', 'like', "ACT{$today}%")
                ->orderBy('service_action_id', 'desc')
                ->first();

            $actionSequence = '001';
            if ($lastAction) {
                $lastActionSequence = substr($lastAction->service_action_id, -3);
                $actionSequence = str_pad((int)$lastActionSequence + 1, 3, '0', STR_PAD_LEFT);
            }

            // Create initial service action
            ServiceAction::create([
                'service_action_id' => "ACT{$today}{$actionSequence}",
                'service_ticket_id' => $ticket->service_ticket_id,
                'action' => 'Tiket Servis Telah Dibuat',
                'number' => 1, // First action is always number 1
                'created_at' => now(),
            ]);

            // Update order service hasTicket status
            \App\Models\OrderService::where('order_service_id', $validated['order_service_id'])
                ->update(['hasTicket' => true]);

            DB::commit();

            return redirect()->route('service-tickets.index')
                ->with('success', 'Tiket servis berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat membuat tiket servis.');
        }
    }

    public function show(ServiceTicket $ticket)
    {
        $ticket->load(['orderService.customer.defaultAddress', 'actions']);
        return view('admin.service-ticket.show', compact('ticket'));
    }

    public function edit(ServiceTicket $ticket)
    {
        return view('admin.service-ticket.edit', compact('ticket'));
    }

    public function update(Request $request, ServiceTicket $ticket)
    {
        $rules = [
            'status' => 'required|in:Menunggu,Diproses,Diantar,Perlu Diambil,Selesai',
            'schedule_date' => 'required|date',
            'estimation_days' => 'nullable|integer|min:1',
        ];

        // Add visit schedule validation only for onsite orders
        if ($ticket->orderService->type === 'onsite') {
            $rules['visit_date'] = 'required|date';
            $rules['visit_time_slot'] = 'required|in:08:00,09:30,11:00,13:00,14:30,16:00';
        }

        $validated = $request->validate($rules);

        // Calculate estimate_date if estimation_days is provided
        if (!empty($validated['estimation_days'])) {
            $scheduleDate = new \DateTime($validated['schedule_date']);
            $validated['estimate_date'] = $scheduleDate->modify("+{$validated['estimation_days']} days")->format('Y-m-d');
        } else {
            $validated['estimate_date'] = null;
            $validated['estimation_days'] = null;
        }

        // Handle visit schedule for onsite orders
        if ($ticket->orderService->type === 'onsite') {
            // Check slot availability
            $isSlotAvailable = $this->checkSlotAvailabilityInternal(
                $ticket->admin_id,
                $validated['visit_date'],
                $validated['visit_time_slot'],
                $ticket->service_ticket_id
            );

            if (!$isSlotAvailable['available']) {
                return back()->withErrors(['visit_time_slot' => $isSlotAvailable['message']])->withInput();
            }

            $validated['visit_schedule'] = $validated['visit_date'] . ' ' . $validated['visit_time_slot'] . ':00';
            unset($validated['visit_date'], $validated['visit_time_slot']);
        }

        $ticket->update($validated);

        return redirect()->route('service-tickets.show', $ticket)
            ->with('success', 'Tiket servis berhasil diperbarui.');
    }

    public function checkSlotAvailability(Request $request)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'visit_date' => 'required|date',
            'visit_time_slot' => 'required|in:08:00,09:30,11:00,13:00,14:30,16:00',
            'exclude_ticket_id' => 'nullable|string'
        ]);

        $result = $this->checkSlotAvailabilityInternal(
            $validated['admin_id'],
            $validated['visit_date'],
            $validated['visit_time_slot'],
            $validated['exclude_ticket_id'] ?? null
        );

        return response()->json($result);
    }

    private function checkSlotAvailabilityInternal($adminId, $visitDate, $timeSlot, $excludeTicketId = null)
    {
        // Check if slot is already taken
        $query = ServiceTicket::where('admin_id', $adminId)
            ->whereDate('visit_schedule', $visitDate)
            ->whereTime('visit_schedule', $timeSlot);

        if ($excludeTicketId) {
            $query->where('service_ticket_id', '!=', $excludeTicketId);
        }

        $isSlotTaken = $query->exists();

        if ($isSlotTaken) {
            return [
                'available' => false,
                'message' => 'Slot sudah diambil'
            ];
        }

        // Check daily visit limit (max 4 visits per technician per day)
        $dailyVisits = ServiceTicket::where('admin_id', $adminId)
            ->whereDate('visit_schedule', $visitDate);

        if ($excludeTicketId) {
            $dailyVisits->where('service_ticket_id', '!=', $excludeTicketId);
        }

        $dailyVisitsCount = $dailyVisits->count();

        if ($dailyVisitsCount >= 4) {
            return [
                'available' => false,
                'message' => 'Teknisi sudah mencapai batas maksimal kunjungan hari ini'
            ];
        }

        return [
            'available' => true,
            'remaining_slots' => 4 - $dailyVisitsCount
        ];
    }

    public function destroy(ServiceTicket $ticket)
    {
        return $this->cancel($ticket);
    }

    public function createAction(ServiceTicket $ticket)
    {
        return view('admin.service-ticket.create-action', compact('ticket'));
    }

    public function storeAction(Request $request, ServiceTicket $ticket)
    {
        $validated = $request->validate([
            'action' => 'required|string',
        ]);

        // Generate action ID with date format
        $today = date('dmy');
        $lastAction = ServiceAction::where('service_action_id', 'like', "ACT{$today}%")
            ->orderBy('service_action_id', 'desc')
            ->first();

        $sequence = '001';
        if ($lastAction) {
            $lastSequence = substr($lastAction->service_action_id, -3);
            $sequence = str_pad((int)$lastSequence + 1, 3, '0', STR_PAD_LEFT);
        }

        // Get the next number for this ticket's actions
        $lastNumber = ServiceAction::where('service_ticket_id', $ticket->service_ticket_id)
            ->max('number') ?? 0;

        ServiceAction::create([
            'service_action_id' => "ACT{$today}{$sequence}",
            'service_ticket_id' => $ticket->service_ticket_id,
            'number' => $lastNumber + 1,
            'action' => $validated['action'],
            'created_at' => now(),
        ]);

        return redirect()->route('service-tickets.show', $ticket)
            ->with('success', 'Tindakan servis berhasil ditambahkan.');
    }

    public function destroyAction(ServiceTicket $ticket, $actionId)
    {
        $action = ServiceAction::where('service_action_id', $actionId)
            ->where('service_ticket_id', $ticket->service_ticket_id)
            ->firstOrFail();

        $action->delete();

        return redirect()->route('service-tickets.show', $ticket)
            ->with('success', 'Tindakan servis berhasil dihapus.');
    }

    public function updateStatus(Request $request, ServiceTicket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Diantar,Perlu Diambil,Selesai,Dibatalkan',
        ]);

        $ticket->update($validated);

        return redirect()->back()
            ->with('success', 'Status tiket servis berhasil diperbarui.');
    }

    public function cancel(ServiceTicket $ticket)
    {
        if ($ticket->status === 'Dibatalkan' || $ticket->status === 'Selesai') {
            return redirect()->back()
                ->with('error', 'Tiket tidak dapat dibatalkan.');
        }

        $ticket->update(['status' => 'Dibatalkan']);

        return redirect()->back()
            ->with('success', 'Tiket servis berhasil dibatalkan.');
    }

    public function recovery()
    {
        $deletedTickets = ServiceTicket::onlyTrashed()->get();
        return view('admin.service-ticket.recovery', compact('deletedTickets'));
    }

    public function restore($id)
    {
        $ticket = ServiceTicket::onlyTrashed()->findOrFail($id);
        $ticket->restore();

        return redirect()->route('service-tickets.recovery')
            ->with('success', 'Tiket servis berhasil dipulihkan.');
    }

    public function forceDelete($id)
    {
        $ticket = ServiceTicket::onlyTrashed()->findOrFail($id);
        $ticket->forceDelete();

        return redirect()->route('service-tickets.recovery')
            ->with('success', 'Tiket servis berhasil dihapus permanen.');
    }
}
