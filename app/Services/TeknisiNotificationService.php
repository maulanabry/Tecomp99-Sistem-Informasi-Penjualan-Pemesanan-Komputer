<?php

namespace App\Services;

use App\Models\ServiceTicket;
use App\Models\Admin;
use App\Enums\NotificationType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TeknisiNotificationService
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Check and create notifications for today's visit schedules
     */
    public function checkTodayVisitSchedules()
    {
        try {
            $today = Carbon::today();

            // Get all tickets with visit schedule for today
            $todayVisits = ServiceTicket::with(['orderService.customer', 'admin'])
                ->whereDate('visit_schedule', $today)
                ->whereIn('status', ['Menunggu', 'Diproses'])
                ->get();

            foreach ($todayVisits as $ticket) {
                if ($ticket->admin && $ticket->admin->role === 'teknisi') {
                    $this->createTodayVisitNotification($ticket);
                }
            }

            Log::info("Checked today's visit schedules: " . $todayVisits->count() . " visits found");
        } catch (\Exception $e) {
            Log::error('Failed to check today visit schedules: ' . $e->getMessage());
        }
    }

    /**
     * Check and create notifications for overdue visit schedules
     */
    public function checkOverdueVisitSchedules()
    {
        try {
            $now = Carbon::now();

            // Get all tickets with overdue visit schedules
            $overdueVisits = ServiceTicket::with(['orderService.customer', 'admin'])
                ->where('visit_schedule', '<', $now)
                ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
                ->get();

            foreach ($overdueVisits as $ticket) {
                // Check if we already sent overdue notification today
                $existingNotification = \App\Models\SystemNotification::where('notifiable_id', $ticket->admin_id)
                    ->where('notifiable_type', Admin::class)
                    ->where('type', NotificationType::TEKNISI_VISIT_OVERDUE)
                    ->whereJsonContains('data->ticket_id', $ticket->service_ticket_id)
                    ->whereDate('created_at', Carbon::today())
                    ->exists();

                if (!$existingNotification && $ticket->admin && $ticket->admin->role === 'teknisi') {
                    $this->createOverdueVisitNotification($ticket);
                }
            }

            Log::info("Checked overdue visit schedules: " . $overdueVisits->count() . " overdue visits found");
        } catch (\Exception $e) {
            Log::error('Failed to check overdue visit schedules: ' . $e->getMessage());
        }
    }

    /**
     * Create notification for today's visit schedule
     */
    private function createTodayVisitNotification(ServiceTicket $ticket)
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

            $visitTime = $ticket->visit_schedule->format('H:i');
            $message = "Anda memiliki kunjungan servis hari ini jam {$visitTime} untuk #{$ticket->service_ticket_id}";

            $data = [
                'ticket_id' => $ticket->service_ticket_id,
                'order_id' => $orderService->order_service_id,
                'customer_name' => $customer->name,
                'device' => $orderService->device,
                'visit_schedule' => $ticket->visit_schedule->format('Y-m-d H:i:s'),
                'visit_time' => $visitTime,
                'type' => $orderService->type
            ];

            $this->notificationService->create(
                notifiable: $ticket->admin,
                type: NotificationType::TEKNISI_VISIT_TODAY,
                subject: $ticket,
                message: $message,
                data: $data
            );
        } catch (\Exception $e) {
            Log::error('Failed to create today visit notification: ' . $e->getMessage());
        }
    }

    /**
     * Create notification for overdue visit schedule
     */
    private function createOverdueVisitNotification(ServiceTicket $ticket)
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

            $message = "Tiket #{$ticket->service_ticket_id} telah melewati jadwal kunjungan yang ditentukan";

            $data = [
                'ticket_id' => $ticket->service_ticket_id,
                'order_id' => $orderService->order_service_id,
                'customer_name' => $customer->name,
                'device' => $orderService->device,
                'visit_schedule' => $ticket->visit_schedule->format('Y-m-d H:i:s'),
                'visit_time' => $ticket->visit_schedule->format('H:i'),
                'type' => $orderService->type,
                'overdue_hours' => $ticket->visit_schedule->diffInHours(Carbon::now())
            ];

            // Notify the assigned teknisi
            $this->notificationService->create(
                notifiable: $ticket->admin,
                type: NotificationType::TEKNISI_VISIT_OVERDUE,
                subject: $ticket,
                message: $message,
                data: $data
            );

            // Also notify admins about overdue visits
            $admins = Admin::where('role', 'admin')->get();
            $adminData = array_merge($data, ['teknisi_name' => $ticket->admin->name]);

            foreach ($admins as $admin) {
                $this->notificationService->create(
                    notifiable: $admin,
                    type: NotificationType::TEKNISI_VISIT_OVERDUE,
                    subject: $ticket,
                    message: "Tiket #{$ticket->service_ticket_id} (Teknisi: {$ticket->admin->name}) telah melewati jadwal kunjungan",
                    data: $adminData
                );
            }
        } catch (\Exception $e) {
            Log::error('Failed to create overdue visit notification: ' . $e->getMessage());
        }
    }

    /**
     * Check visit schedules for a specific teknisi (for dashboard/login)
     */
    public function checkTeknisiTodaySchedule(Admin $teknisi)
    {
        try {
            $today = Carbon::today();

            // Get today's visits for this teknisi
            $todayVisits = ServiceTicket::with(['orderService.customer'])
                ->where('admin_id', $teknisi->id)
                ->whereDate('visit_schedule', $today)
                ->whereIn('status', ['Menunggu', 'Diproses'])
                ->get();

            foreach ($todayVisits as $ticket) {
                // Check if we already sent today's notification
                $existingNotification = \App\Models\SystemNotification::where('notifiable_id', $teknisi->id)
                    ->where('notifiable_type', Admin::class)
                    ->where('type', NotificationType::TEKNISI_VISIT_TODAY)
                    ->whereJsonContains('data->ticket_id', $ticket->service_ticket_id)
                    ->whereDate('created_at', $today)
                    ->exists();

                if (!$existingNotification) {
                    $this->createTodayVisitNotification($ticket);
                }
            }

            return $todayVisits->count();
        } catch (\Exception $e) {
            Log::error('Failed to check teknisi today schedule: ' . $e->getMessage());
            return 0;
        }
    }
}
