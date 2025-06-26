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

                // Add service duration event
                if ($ticket->schedule_date && $ticket->estimate_date) {
                    $events[] = [
                        'id' => 'duration_' . $ticket->id,
                        'title' => "Service #" . $ticket->service_ticket_id,
                        'start' => $ticket->schedule_date,
                        'end' => $ticket->estimate_date,
                        'backgroundColor' => '#3788d8',
                        'borderColor' => '#3788d8',
                        'extendedProps' => [
                            'ticket_id' => $ticket->service_ticket_id,
                            'customer_name' => $ticket->orderService->customer->name,
                            'type' => $ticket->orderService->type,
                            'device' => $ticket->orderService->device,
                            'eventType' => 'duration'
                        ]
                    ];
                }

                // Add visit schedule event for onsite services
                if ($ticket->orderService->type === 'onsite' && $ticket->visit_schedule) {
                    $events[] = [
                        'id' => 'visit_' . $ticket->id,
                        'title' => "Visit #" . $ticket->service_ticket_id,
                        'start' => $ticket->visit_schedule,
                        'end' => \Carbon\Carbon::parse($ticket->visit_schedule)->addHour(),
                        'backgroundColor' => '#dc3545',
                        'borderColor' => '#dc3545',
                        'extendedProps' => [
                            'ticket_id' => $ticket->service_ticket_id,
                            'customer_name' => $ticket->orderService->customer->name,
                            'type' => $ticket->orderService->type,
                            'device' => $ticket->orderService->device,
                            'eventType' => 'visit'
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
        $tickets = ServiceTicket::with(['orderService.customer'])
            ->whereHas('orderService', function ($query) {
                $query->whereNotNull('type');
            })
            ->get()
            ->map(function ($ticket) {
                $events = [];

                // Add service duration event
                if ($ticket->schedule_date && $ticket->estimate_date) {
                    $events[] = [
                        'id' => 'duration_' . $ticket->id,
                        'title' => "Service #" . $ticket->service_ticket_id,
                        'start' => $ticket->schedule_date,
                        'end' => $ticket->estimate_date,
                        'backgroundColor' => '#3788d8',
                        'borderColor' => '#3788d8',
                        'extendedProps' => [
                            'ticket_id' => $ticket->service_ticket_id,
                            'customer_name' => $ticket->orderService->customer->name,
                            'type' => $ticket->orderService->type,
                            'device' => $ticket->orderService->device,
                            'eventType' => 'duration'
                        ]
                    ];
                }

                // Add visit schedule event for onsite services
                if ($ticket->orderService->type === 'onsite' && $ticket->visit_schedule) {
                    $events[] = [
                        'id' => 'visit_' . $ticket->id,
                        'title' => "Visit #" . $ticket->service_ticket_id,
                        'start' => $ticket->visit_schedule,
                        'end' => \Carbon\Carbon::parse($ticket->visit_schedule)->addHour(),
                        'backgroundColor' => '#dc3545',
                        'borderColor' => '#dc3545',
                        'extendedProps' => [
                            'ticket_id' => $ticket->service_ticket_id,
                            'customer_name' => $ticket->orderService->customer->name,
                            'type' => $ticket->orderService->type,
                            'device' => $ticket->orderService->device,
                            'eventType' => 'visit'
                        ]
                    ];
                }

                return $events;
            })
            ->flatten(1)
            ->values();

        return response()->json($tickets);
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

        // Add visit_schedule validation only for onsite orders
        if ($orderService && $orderService->type === 'onsite') {
            $rules['visit_schedule'] = 'nullable|date_format:Y-m-d\TH:i';
        }

        $validated = $request->validate($rules);

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
        $ticket->load(['orderService.customer', 'actions']);
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

        // Add visit_schedule validation only for onsite orders
        if ($ticket->orderService->type === 'onsite') {
            $rules['visit_schedule'] = 'nullable|date_format:Y-m-d\TH:i';
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

        $ticket->update($validated);

        return redirect()->route('service-tickets.show', $ticket)
            ->with('success', 'Tiket servis berhasil diperbarui.');
    }

    public function destroy(ServiceTicket $ticket)
    {
        $ticket->delete();

        return redirect()->route('service-tickets.index')
            ->with('success', 'Tiket servis berhasil dihapus.');
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
