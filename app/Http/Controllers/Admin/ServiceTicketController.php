<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceTicket;
use App\Models\ServiceAction;
use App\Models\Admin;
use App\Services\NotificationService;
use App\Enums\NotificationType;
use Illuminate\Http\Request;
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
        return view('admin.service-ticket');
    }

    public function cards()
    {
        return view('admin.service-ticket-cards');
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
        // Debug: Log all request data
        \Log::info('ServiceTicket Store Request:', $request->all());

        // Get the order service to check its type
        $orderService = \App\Models\OrderService::find($request->order_service_id);

        \Log::info('OrderService found:', $orderService ? $orderService->toArray() : 'null');

        $rules = [
            'order_service_id' => 'required|exists:order_services,order_service_id',
            'admin_id' => 'required|exists:admins,id',
            'schedule_date' => 'required|date|after_or_equal:today',
            'estimation_days' => 'nullable|integer|min:1',
        ];

        // Add visit schedule validation only for onsite orders
        if ($orderService && $orderService->type === 'onsite') {
            $rules['visit_date'] = 'required|date';
            $rules['visit_time_slot'] = 'required|in:08:00,10:30,13:00,15:30,18:00';
        }

        $validated = $request->validate($rules);

        // Handle visit schedule for onsite orders
        if ($orderService && $orderService->type === 'onsite') {
            // Check slot availability
            $isSlotAvailable = $this->checkSlotAvailabilityInternal(
                $validated['admin_id'],
                $validated['visit_date'],
                $validated['visit_time_slot'],
                null,
                $orderService->order_service_id
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
        $validated['status'] = 'menunggu'; // Set to menunggu when created

        // Begin transaction
        DB::beginTransaction();
        try {
            // Set session flag to bypass validation during creation
            session(['bypass_ticket_validation' => true]);

            // Create service ticket
            $ticket = ServiceTicket::create($validated);

            // Clear session flag
            session()->forget('bypass_ticket_validation');

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
                'action' => 'Tiket Servis Telah Dibuat dan Menunggu Konfirmasi',
                'number' => 1, // First action is always number 1
                'created_at' => now(),
            ]);

            // Update order service hasTicket status and set status to Diproses
            \App\Models\OrderService::where('order_service_id', $validated['order_service_id'])
                ->update([
                    'hasTicket' => true,
                    'status_order' => 'menunggu'
                ]);

            DB::commit();

            // Create notification for new service ticket
            try {
                $this->createTicketNotification(
                    $ticket,
                    NotificationType::SERVICE_TICKET_CREATED,
                    "Tiket servis baru dibuat dan menunggu konfirmasi untuk {$ticket->orderService->device}"
                );

                // Notify the assigned teknisi
                $assignedTeknisi = Admin::find($validated['admin_id']);
                if ($assignedTeknisi && $assignedTeknisi->role === 'teknisi') {
                    $this->createTeknisiAssignmentNotification($ticket, $assignedTeknisi);
                }
            } catch (\Exception $e) {
                Log::error('Failed to create service ticket notification: ' . $e->getMessage());
            }

            return redirect()->route('service-tickets.index')
                ->with('success', 'Tiket servis berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollback();
            // Clear session flag on error
            session()->forget('bypass_ticket_validation');
            \Log::error('Error creating service ticket:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat membuat tiket servis: ' . $e->getMessage());
        }
    }

    public function show(ServiceTicket $ticket)
    {
        $ticket->load(['orderService.customer.defaultAddress', 'actions']);
        return view('admin.service-ticket.show', compact('ticket'));
    }

    public function edit(ServiceTicket $ticket)
    {
        $technicians = \App\Models\Admin::where('role', 'teknisi')->get();
        return view('admin.service-ticket.edit', compact('ticket', 'technicians'));
    }

    public function update(Request $request, ServiceTicket $ticket)
    {
        $rules = [
            'status' => 'required|in:menunggu,dijadwalkan,menuju_lokasi,diproses,menunggu_sparepart,siap_diambil,diantar,selesai,dibatalkan,melewati_jatuh_tempo',
            'admin_id' => 'required|exists:admins,id',
            'schedule_date' => 'required|date',
            'estimation_days' => 'nullable|integer|min:1',
        ];

        // Add visit schedule validation only for onsite orders
        if ($ticket->orderService->type === 'onsite') {
            $rules['visit_date'] = 'required|date';
            $rules['visit_time_slot'] = 'required|in:08:00,10:30,13:00,15:30,18:00';
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
            // Check slot availability with the new admin_id
            $isSlotAvailable = $this->checkSlotAvailabilityInternal(
                $validated['admin_id'],
                $validated['visit_date'],
                $validated['visit_time_slot'],
                $ticket->service_ticket_id,
                $ticket->orderService->order_service_id
            );

            if (!$isSlotAvailable['available']) {
                return back()->withErrors(['visit_time_slot' => $isSlotAvailable['message']])->withInput();
            }

            $validated['visit_schedule'] = $validated['visit_date'] . ' ' . $validated['visit_time_slot'] . ':00';

            unset($validated['visit_date'], $validated['visit_time_slot']);
        }

        $oldStatus = $ticket->status;
        $oldAdminId = $ticket->admin_id;

        // Set session flag to bypass validation during update
        session(['bypass_ticket_validation' => true]);

        $ticket->update($validated);

        // Clear session flag
        session()->forget('bypass_ticket_validation');

        // Sync OrderService status if ticket status changed
        if ($oldStatus !== $ticket->status) {
            $this->syncOrderServiceStatus($ticket, $oldStatus);
        }

        // Create notification for ticket update if status changed
        if ($oldStatus !== $ticket->status) {
            try {
                $type = match ($ticket->status) {
                    'selesai' => NotificationType::SERVICE_TICKET_COMPLETED,
                    default => NotificationType::SERVICE_TICKET_UPDATED
                };

                $message = match ($ticket->status) {
                    'selesai' => "Tiket servis #{$ticket->service_ticket_id} telah selesai",
                    'diproses' => "Tiket servis #{$ticket->service_ticket_id} sedang diproses",
                    'diantar' => "Tiket servis #{$ticket->service_ticket_id} sedang diantar",
                    'siap_diambil' => "Tiket servis #{$ticket->service_ticket_id} siap diambil",
                    default => "Status tiket servis #{$ticket->service_ticket_id} diubah menjadi {$ticket->status}"
                };

                $this->createTicketNotification($ticket, $type, $message);
            } catch (\Exception $e) {
                Log::error('Failed to create service ticket update notification: ' . $e->getMessage());
            }
        }

        // Create notification for technician assignment change
        if ($oldAdminId !== $ticket->admin_id) {
            try {
                // Notify the new assigned teknisi
                $newTeknisi = Admin::find($ticket->admin_id);
                if ($newTeknisi && $newTeknisi->role === 'teknisi') {
                    $this->createTeknisiAssignmentNotification($ticket, $newTeknisi);
                }

                // Create general notification for admin about technician change
                $oldTeknisiName = $oldAdminId ? Admin::find($oldAdminId)?->name : 'Tidak ada';
                $newTeknisiName = $newTeknisi ? $newTeknisi->name : 'Tidak ada';

                $this->createTicketNotification(
                    $ticket,
                    NotificationType::SERVICE_TICKET_UPDATED,
                    "Teknisi tiket servis #{$ticket->service_ticket_id} diubah dari {$oldTeknisiName} ke {$newTeknisiName}"
                );
            } catch (\Exception $e) {
                Log::error('Failed to create technician assignment change notification: ' . $e->getMessage());
            }
        }

        return redirect()->route('service-tickets.show', $ticket)
            ->with('success', 'Tiket servis berhasil diperbarui.');
    }

    /**
     * Mengecek ketersediaan slot waktu kunjungan untuk teknisi tertentu
     * Endpoint untuk AJAX request dari form create/edit service ticket
     */
    public function checkSlotAvailability(Request $request)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'visit_date' => 'required|date',
            'visit_time_slot' => 'required|in:08:00,10:30,13:00,15:30,18:00',
            'exclude_ticket_id' => 'nullable|string',
            'exclude_order_service_id' => 'nullable|string'
        ]);

        $result = $this->checkSlotAvailabilityInternal(
            $validated['admin_id'],
            $validated['visit_date'],
            $validated['visit_time_slot'],
            $validated['exclude_ticket_id'] ?? null,
            $validated['exclude_order_service_id'] ?? null
        );

        return response()->json($result);
    }

    /**
     * Mendapatkan semua slot yang sudah dibooking untuk tanggal dan teknisi tertentu
     * Digunakan untuk menampilkan slot yang tidak tersedia di frontend
     */
    public function getBookedSlotsForDate(Request $request)
    {
        $validated = $request->validate([
            'admin_id' => 'required|exists:admins,id',
            'visit_date' => 'required|date',
            'exclude_ticket_id' => 'nullable|string',
            'exclude_order_service_id' => 'nullable|string'
        ]);

        // Query untuk mendapatkan semua OrderService yang sudah dibooking untuk tanggal tertentu
        $query = \App\Models\OrderService::where('visit_date', $validated['visit_date'])
            ->whereNotNull('visit_slot')
            ->where('type', 'onsite') // Hanya untuk onsite services
            ->with(['customer', 'tickets']);

        // Exclude order service tertentu jika sedang edit
        if (!empty($validated['exclude_order_service_id'])) {
            $query->where('order_service_id', '!=', $validated['exclude_order_service_id']);
        }

        // Debug: Log query
        \Log::info('GetBookedSlots Query:', [
            'admin_id' => $validated['admin_id'],
            'visit_date' => $validated['visit_date'],
            'exclude_ticket_id' => $validated['exclude_ticket_id'] ?? null,
            'exclude_order_service_id' => $validated['exclude_order_service_id'] ?? null,
            'query_sql' => $query->toSql(),
            'query_bindings' => $query->getBindings()
        ]);

        // Ambil semua slot yang sudah dibooking
        $bookedSlots = $query->get()
            ->map(function ($orderService) use ($validated) {
                // Extract time slot from visit_slot (e.g., "08:00 - 09:30" -> "08:00")
                $timeSlot = explode(' - ', $orderService->visit_slot)[0];

                // Check if technician is assigned
                $ticket = $orderService->tickets->first();
                $technicianAssigned = $ticket && $ticket->admin_id;
                $assignedToCurrentTechnician = $technicianAssigned && $ticket->admin_id == $validated['admin_id'];

                return [
                    'time_slot' => $timeSlot,
                    'customer_name' => $orderService->customer->name ?? 'Unknown',
                    'order_service_id' => $orderService->order_service_id,
                    'device' => $orderService->device ?? '',
                    'technician_assigned' => $technicianAssigned,
                    'assigned_to_current' => $assignedToCurrentTechnician,
                    'ticket_id' => $ticket ? $ticket->service_ticket_id : null,
                    'slot_disabled' => true // Disable all booked slots to prevent double booking
                ];
            })
            ->toArray();

        // Debug: Log hasil query
        \Log::info('Booked slots found:', [
            'count' => count($bookedSlots),
            'slots' => $bookedSlots
        ]);

        // Hitung total kunjungan hari ini untuk teknisi ini
        $technicianVisitsToday = collect($bookedSlots)->where('assigned_to_current', true)->count();
        $maxVisitsPerDay = 4;
        $remainingSlots = max(0, $maxVisitsPerDay - $technicianVisitsToday);

        $response = [
            'booked_slots' => $bookedSlots,
            'total_visits_today' => $technicianVisitsToday,
            'remaining_slots' => $remainingSlots,
            'max_visits_per_day' => $maxVisitsPerDay,
            'date_full' => $technicianVisitsToday >= $maxVisitsPerDay,
            'debug_info' => [
                'admin_id' => $validated['admin_id'],
                'visit_date' => $validated['visit_date'],
                'query_executed' => true
            ]
        ];

        \Log::info('GetBookedSlots Response:', $response);

        return response()->json($response);
    }

    /**
     * Method internal untuk mengecek ketersediaan slot waktu tertentu
     * Digunakan oleh checkSlotAvailability dan proses validasi form
     */
    private function checkSlotAvailabilityInternal($adminId, $visitDate, $timeSlot, $excludeTicketId = null, $excludeOrderServiceId = null)
    {
        // Debug: Log parameter yang diterima
        \Log::info('CheckSlotAvailabilityInternal called:', [
            'admin_id' => $adminId,
            'visit_date' => $visitDate,
            'time_slot' => $timeSlot,
            'exclude_ticket_id' => $excludeTicketId,
            'exclude_order_service_id' => $excludeOrderServiceId
        ]);

        // Cek apakah slot waktu spesifik sudah diambil
        $slotString = $timeSlot . ' - ' . date('H:i', strtotime($timeSlot) + 5400); // Add 1.5 hours
        $query = \App\Models\OrderService::where('visit_date', $visitDate)
            ->where('visit_slot', $slotString)
            ->where('type', 'onsite')
            ->with(['customer', 'tickets']);

        // Exclude order service tertentu jika sedang edit
        if ($excludeOrderServiceId) {
            $query->where('order_service_id', '!=', $excludeOrderServiceId);
        }

        // Debug: Log query untuk slot spesifik
        \Log::info('Slot availability query:', [
            'query_sql' => $query->toSql(),
            'query_bindings' => $query->getBindings(),
            'slot_string' => $slotString
        ]);

        $orderService = $query->first();

        // Jika slot sudah diambil
        if ($orderService) {
            $ticket = $orderService->tickets->first();
            $customerName = $orderService->customer->name ?? 'Unknown';

            \Log::info('Slot taken by:', [
                'customer_name' => $customerName,
                'order_service_id' => $orderService->order_service_id,
                'ticket_id' => $ticket ? $ticket->service_ticket_id : null
            ]);

            // Slot sudah dipesan, tidak tersedia untuk booking baru
            return [
                'available' => false,
                'message' => "Slot sudah dipesan oleh {$customerName}",
                'reason' => 'slot_taken',
                'existing_ticket' => $ticket ? $ticket->service_ticket_id : null
            ];
        }

        // Cek batas maksimal kunjungan harian (maksimal 4 kunjungan per teknisi per hari)
        $dailyVisitsQuery = ServiceTicket::where('admin_id', $adminId)
            ->whereDate('visit_schedule', $visitDate)
            ->whereNotNull('visit_schedule')
            ->whereHas('orderService', function ($q) {
                $q->where('type', 'onsite'); // Hanya untuk onsite services
            });

        if ($excludeTicketId) {
            $dailyVisitsQuery->where('service_ticket_id', '!=', $excludeTicketId);
        }

        // Debug: Log query untuk daily visits
        \Log::info('Daily visits query:', [
            'query_sql' => $dailyVisitsQuery->toSql(),
            'query_bindings' => $dailyVisitsQuery->getBindings()
        ]);

        $dailyVisitsCount = $dailyVisitsQuery->count();
        $maxVisitsPerDay = 4;

        \Log::info('Daily visits count:', [
            'current_visits' => $dailyVisitsCount,
            'max_visits' => $maxVisitsPerDay
        ]);

        // Jika sudah mencapai batas maksimal kunjungan
        if ($dailyVisitsCount >= $maxVisitsPerDay) {
            return [
                'available' => false,
                'message' => 'Teknisi sudah mencapai batas maksimal kunjungan hari ini (4 kunjungan)',
                'reason' => 'daily_limit_reached',
                'current_visits' => $dailyVisitsCount,
                'max_visits' => $maxVisitsPerDay
            ];
        }

        // Slot tersedia
        $result = [
            'available' => true,
            'message' => 'Slot tersedia',
            'remaining_slots' => $maxVisitsPerDay - $dailyVisitsCount,
            'current_visits' => $dailyVisitsCount,
            'max_visits' => $maxVisitsPerDay
        ];

        \Log::info('Slot availability result:', $result);

        return $result;
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
            'status' => 'required|in:menunggu,dijadwalkan,menuju_lokasi,diproses,menunggu_sparepart,siap_diambil,diantar,selesai,dibatalkan,melewati_jatuh_tempo',
        ]);

        try {
            DB::beginTransaction();

            $oldStatus = $ticket->status;

            // Set session flag to skip validation during sync
            session(['syncing_ticket_status' => true]);
            session(['bypass_ticket_validation' => true]);

            $ticket->update($validated);

            // Synchronize with OrderService status
            $this->syncOrderServiceStatus($ticket, $oldStatus);

            // Clear session flag
            session()->forget('syncing_ticket_status');

            // Create notification for ticket update if status changed
            if ($oldStatus !== $ticket->status) {
                try {
                    $type = match ($ticket->status) {
                        'selesai' => NotificationType::SERVICE_TICKET_COMPLETED,
                        default => NotificationType::SERVICE_TICKET_UPDATED
                    };

                    $message = match ($ticket->status) {
                        'selesai' => "Tiket servis #{$ticket->service_ticket_id} telah selesai",
                        'diproses' => "Tiket servis #{$ticket->service_ticket_id} sedang diproses",
                        'diantar' => "Tiket servis #{$ticket->service_ticket_id} sedang diantar",
                        'siap_diambil' => "Tiket servis #{$ticket->service_ticket_id} siap diambil",
                        'menuju_lokasi' => "Teknisi sedang menuju lokasi untuk tiket #{$ticket->service_ticket_id}",
                        'menunggu_sparepart' => "Tiket #{$ticket->service_ticket_id} menunggu sparepart",
                        'dijadwalkan' => "Tiket #{$ticket->service_ticket_id} telah dijadwalkan",
                        default => "Status tiket servis #{$ticket->service_ticket_id} diubah menjadi {$ticket->status}"
                    };

                    $this->createTicketNotification($ticket, $type, $message);
                } catch (\Exception $e) {
                    Log::error('Failed to create service ticket update notification: ' . $e->getMessage());
                }
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'Status tiket servis berhasil diperbarui.');
        } catch (\Exception $e) {
            // Clear session flag on error
            session()->forget('syncing_ticket_status');

            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui status tiket servis: ' . $e->getMessage());
        }
    }

    public function cancel(ServiceTicket $ticket)
    {
        if ($ticket->status === 'dibatalkan' || $ticket->status === 'selesai') {
            return redirect()->back()
                ->with('error', 'Tiket tidak dapat dibatalkan.');
        }

        try {
            DB::beginTransaction();

            $oldStatus = $ticket->status;

            // Set session flag to skip validation during sync
            session(['syncing_ticket_status' => true]);
            session(['bypass_ticket_validation' => true]);

            $ticket->update(['status' => 'dibatalkan']);

            // Synchronize with OrderService status
            $this->syncOrderServiceStatus($ticket, $oldStatus);

            // Clear session flag
            session()->forget('syncing_ticket_status');

            DB::commit();

            return redirect()->back()
                ->with('success', 'Tiket servis berhasil dibatalkan.');
        } catch (\Exception $e) {
            // Clear session flag on error
            session()->forget('syncing_ticket_status');

            DB::rollback();
            return redirect()->back()
                ->with('error', 'Gagal membatalkan tiket servis: ' . $e->getMessage());
        }
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

    /**
     * Create service ticket notification for all admins
     */
    private function createTicketNotification(ServiceTicket $ticket, NotificationType $type, string $message)
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
                'type' => $orderService->type // reguler/onsite
            ];

            // Add teknisi name if assigned
            if ($ticket->admin && $ticket->admin->role === 'teknisi') {
                $data['teknisi_name'] = $ticket->admin->name;
            }

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
     * Create assignment notification for teknisi
     */
    private function createTeknisiAssignmentNotification(ServiceTicket $ticket, Admin $teknisi)
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
                'type' => $orderService->type
            ];

            // Create message based on service type
            if ($orderService->type === 'onsite' && $ticket->visit_schedule) {
                $visitDate = $ticket->visit_schedule->format('d/m/Y');
                $visitTime = $ticket->visit_schedule->format('H:i');
                $message = "Anda ditugaskan untuk kunjungan servis #{$ticket->service_ticket_id} pada {$visitDate}, jam {$visitTime}";

                $data['visit_schedule'] = $ticket->visit_schedule->format('Y-m-d H:i:s');
                $data['visit_time'] = $visitTime;
            } else {
                $message = "Anda ditugaskan untuk tiket servis #{$ticket->service_ticket_id} yang menunggu konfirmasi - {$orderService->device}";
            }

            $this->notificationService->create(
                notifiable: $teknisi,
                type: NotificationType::TEKNISI_ASSIGNED_TICKET,
                subject: $ticket,
                message: $message,
                data: $data
            );
        } catch (\Exception $e) {
            Log::error('Failed to create teknisi assignment notification: ' . $e->getMessage());
        }
    }

    /**
     * Synchronize OrderService status with ServiceTicket status
     */
    private function syncOrderServiceStatus(ServiceTicket $ticket, string $oldTicketStatus)
    {
        $orderService = $ticket->orderService;
        if (!$orderService) {
            return;
        }

        // Status mapping from ServiceTicket to OrderService
        $statusMapping = [
            'menunggu' => 'Menunggu',
            'dijadwalkan' => 'Dijadwalkan',
            'menuju_lokasi' => 'Menuju_lokasi',
            'diproses' => 'Diproses',
            'menunggu_sparepart' => 'Menunggu_sparepart',
            'siap_diambil' => 'Siap_diambil',
            'diantar' => 'Diantar',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            'melewati_jatuh_tempo' => 'Melewati_jatuh_tempo',
        ];

        $newOrderStatus = $statusMapping[$ticket->status] ?? $orderService->status_order;

        // Only update if the status actually changed
        if ($orderService->status_order !== $newOrderStatus) {
            $orderService->update(['status_order' => $newOrderStatus]);

            // Create service action for the order service as well
            $this->createOrderServiceAction($orderService, $newOrderStatus, $orderService->status_order);
        }
    }

    /**
     * Create service action for OrderService audit trail
     */
    private function createOrderServiceAction($orderService, $newStatus, $oldStatus)
    {
        // Define action descriptions based on status
        $actionDescriptions = [
            'Menunggu' => 'Order servis menunggu konfirmasi',
            'Dijadwalkan' => 'Order servis telah dijadwalkan',
            'Menuju_lokasi' => 'Teknisi sedang menuju lokasi',
            'Diproses' => 'Order servis sedang diproses',
            'Menunggu_sparepart' => 'Order servis menunggu sparepart',
            'Siap_diambil' => 'Order servis siap diambil pelanggan',
            'Diantar' => 'Order servis sedang diantar ke pelanggan',
            'Selesai' => 'Order servis telah selesai',
            'Dibatalkan' => 'Order servis dibatalkan',
            'Melewati_jatuh_tempo' => 'Order servis kedaluwarsa',
        ];

        $action = $actionDescriptions[$newStatus] ?? 'Status order diperbarui';

        // Get the first ticket for this order service
        $firstTicket = $orderService->tickets()->first();
        if (!$firstTicket) {
            return; // No ticket found, skip creating action
        }

        // Get the next number for this ticket's actions
        $nextNumber = \App\Models\ServiceAction::where('service_ticket_id', $firstTicket->service_ticket_id)
            ->max('number') + 1;

        // Generate unique service action ID with timestamp and random component
        do {
            $timestamp = now()->format('ymdHis');
            $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            $actionId = 'OSA' . $timestamp . $random;
            $exists = \App\Models\ServiceAction::where('service_action_id', $actionId)->exists();
        } while ($exists);

        \App\Models\ServiceAction::create([
            'service_action_id' => $actionId,
            'service_ticket_id' => $firstTicket->service_ticket_id,
            'number' => $nextNumber,
            'action' => $action,
            'created_at' => now(),
        ]);
    }
}
