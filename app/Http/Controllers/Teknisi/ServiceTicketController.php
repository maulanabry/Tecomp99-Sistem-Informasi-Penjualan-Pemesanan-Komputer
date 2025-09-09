<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Models\ServiceTicket;
use App\Models\ServiceAction;
use App\Models\OrderService;
use App\Models\Admin;
use App\Services\NotificationService;
use App\Enums\NotificationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceTicketController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        return view('teknisi.service-ticket.index');
    }

    public function create(Request $request)
    {
        // Get order services that don't have tickets yet
        $orderServices = OrderService::where('hasTicket', false)
            ->with('customer')
            ->get();

        // Get all technicians (admins with teknisi role)
        $technicians = Admin::where('role', 'teknisi')->get();

        // If order_service_id is provided in query params, pre-select it
        $selectedOrderServiceId = $request->query('order_service_id');

        return view('teknisi.service-ticket.create', compact('orderServices', 'technicians', 'selectedOrderServiceId'));
    }

    public function store(Request $request)
    {
        // Get the order service to check its type
        $orderService = OrderService::find($request->order_service_id);

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

            // Generate unique service action ID with timestamp and random component
            do {
                $timestamp = now()->format('ymdHis');
                $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                $actionId = 'ACT' . $timestamp . $random;
                $exists = ServiceAction::where('service_action_id', $actionId)->exists();
            } while ($exists);

            // Create initial service action
            ServiceAction::create([
                'service_action_id' => $actionId,
                'service_ticket_id' => $ticket->service_ticket_id,
                'action' => 'Tiket Servis Telah Dibuat',
                'number' => 1, // First action is always number 1
                'created_at' => now(),
            ]);

            // Update order service hasTicket status and set status to Diproses
            OrderService::where('order_service_id', $validated['order_service_id'])
                ->update([
                    'hasTicket' => true,
                    'status_order' => 'Diproses'
                ]);

            DB::commit();

            // Create notification for new service ticket created by teknisi
            try {
                $teknisi = auth('teknisi')->user();
                $this->createTicketNotificationForAdmin(
                    $ticket,
                    NotificationType::TEKNISI_TICKET_CREATED,
                    "Tiket servis berhasil dibuat oleh Teknisi {$teknisi->name} untuk order #{$ticket->orderService->order_service_id}"
                );
            } catch (\Exception $e) {
                Log::error('Failed to create service ticket notification: ' . $e->getMessage());
            }

            return redirect()->route('teknisi.service-tickets.index')
                ->with('success', 'Tiket servis berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat membuat tiket servis.');
        }
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

    public function calendar()
    {
        $tickets = ServiceTicket::with(['orderService.customer'])
            ->where('admin_id', Auth::guard('teknisi')->id()) // Only show tickets assigned to current teknisi
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

        return view('teknisi.service-ticket.calendar', compact('tickets'));
    }

    public function calendarEvents()
    {
        $serviceTickets = ServiceTicket::with(['orderService.customer'])
            ->where('admin_id', Auth::guard('teknisi')->id()) // Only show tickets assigned to current teknisi
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

    public function show(Request $request, ServiceTicket $ticket)
    {
        $ticket->load(['orderService.customer.defaultAddress', 'admin', 'actions']);
        $previousUrl = url()->previous();
        return view('teknisi.service-ticket.show', compact('ticket', 'previousUrl'));
    }

    public function updateStatus(Request $request, ServiceTicket $ticket)
    {
        $request->validate([
            'status' => 'required|in:Menunggu,Diproses,Diantar,Perlu Diambil,Selesai'
        ]);

        $oldStatus = $ticket->status;
        $ticket->update([
            'status' => $request->status
        ]);

        // Create notification for status update by teknisi
        if ($oldStatus !== $request->status) {
            try {
                $teknisi = auth('teknisi')->user();
                $this->createTicketNotificationForAdmin(
                    $ticket,
                    NotificationType::TEKNISI_ORDER_UPDATED,
                    "Tiket #{$ticket->service_ticket_id} telah diperbarui oleh Teknisi {$teknisi->name} - Status: {$request->status}"
                );
            } catch (\Exception $e) {
                Log::error('Failed to create status update notification: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Status tiket berhasil diperbarui.');
    }

    public function storeAction(Request $request, ServiceTicket $ticket)
    {
        $request->validate([
            'action' => 'required|string|max:500'
        ]);

        // Get the next number for this ticket's actions
        $lastNumber = ServiceAction::where('service_ticket_id', $ticket->service_ticket_id)
            ->max('number') ?? 0;

        // Generate unique service action ID with timestamp and random component
        do {
            $timestamp = now()->format('ymdHis');
            $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $actionId = 'ACT' . $timestamp . $random;
            $exists = ServiceAction::where('service_action_id', $actionId)->exists();
        } while ($exists);

        ServiceAction::create([
            'service_action_id' => $actionId,
            'service_ticket_id' => $ticket->service_ticket_id,
            'number' => $lastNumber + 1,
            'action' => $request->action,
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Tindakan berhasil ditambahkan.');
    }

    public function destroyAction(ServiceTicket $ticket, ServiceAction $action)
    {
        if ($action->service_ticket_id !== $ticket->service_ticket_id) {
            abort(404);
        }

        $action->delete();

        return redirect()->back()->with('success', 'Tindakan berhasil dihapus.');
    }

    /**
     * Create service ticket notification for all admins (when teknisi creates/updates)
     */
    private function createTicketNotificationForAdmin(ServiceTicket $ticket, NotificationType $type, string $message)
    {
        try {
            $orderService = $ticket->orderService;
            if (!$orderService) {
                return;
            }

            $customer = $orderService->customer;
            if (!$customer) {
                return;
            }

            // Prepare notification data
            $data = [
                'ticket_id' => $ticket->service_ticket_id,
                'order_id' => $orderService->order_service_id,
                'customer_name' => $customer->name,
                'device' => $orderService->device,
                'status' => $ticket->status,
                'type' => $orderService->type, // reguler/onsite
                'teknisi_name' => auth('teknisi')->user()->name
            ];

            // Add visit schedule for onsite service
            if ($orderService->type === 'onsite' && $ticket->visit_schedule) {
                $data['visit_schedule'] = $ticket->visit_schedule->format('Y-m-d H:i:s');
                $data['visit_time'] = $ticket->visit_schedule->format('H:i');
            }

            // Create notifications for all admins
            $admins = Admin::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $this->notificationService->create(
                    notifiable: $admin,
                    type: $type,
                    subject: $ticket,
                    message: $message,
                    data: $data
                );
            }
        } catch (\Exception $e) {
            Log::error('Failed to create ticket notification: ' . $e->getMessage());
        }
    }

    /**
     * Create notification for teknisi about their assignments or schedules
     */
    private function createTeknisiNotification(Admin $teknisi, NotificationType $type, ServiceTicket $ticket, string $message, array $additionalData = [])
    {
        try {
            $orderService = $ticket->orderService;
            if (!$orderService) {
                return;
            }

            $customer = $orderService->customer;
            if (!$customer) {
                return;
            }

            // Prepare notification data
            $data = array_merge([
                'ticket_id' => $ticket->service_ticket_id,
                'order_id' => $orderService->order_service_id,
                'customer_name' => $customer->name,
                'device' => $orderService->device,
                'status' => $ticket->status,
                'type' => $orderService->type
            ], $additionalData);

            // Add visit schedule for onsite service
            if ($orderService->type === 'onsite' && $ticket->visit_schedule) {
                $data['visit_schedule'] = $ticket->visit_schedule->format('Y-m-d H:i:s');
                $data['visit_time'] = $ticket->visit_schedule->format('H:i');
            }

            $this->notificationService->create(
                notifiable: $teknisi,
                type: $type,
                subject: $ticket,
                message: $message,
                data: $data
            );
        } catch (\Exception $e) {
            Log::error('Failed to create teknisi notification: ' . $e->getMessage());
        }
    }
}
