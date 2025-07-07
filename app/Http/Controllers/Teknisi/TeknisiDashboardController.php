<?php

namespace App\Http\Controllers\Teknisi;

use App\Http\Controllers\Controller;
use App\Services\TeknisiNotificationService;
use App\Models\ServiceTicket;
use App\Models\OrderService;
use App\Models\SystemNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TeknisiDashboardController extends Controller
{
    protected $teknisiNotificationService;

    public function __construct(TeknisiNotificationService $teknisiNotificationService)
    {
        $this->teknisiNotificationService = $teknisiNotificationService;
    }

    public function index(): View
    {
        $teknisi = auth('teknisi')->user();
        $teknisiId = Auth::guard('teknisi')->id();
        $today = Carbon::today();

        // Check for today's visit schedules and create notifications
        $todayVisitsCount = $this->teknisiNotificationService->checkTeknisiTodaySchedule($teknisi);

        // Get comprehensive dashboard data
        $dashboardData = $this->getDashboardData($teknisiId, $today);

        return view('teknisi.dashboard', array_merge([
            'teknisi' => $teknisi,
            'todayVisitsCount' => $todayVisitsCount
        ], $dashboardData));
    }

    /**
     * Get comprehensive dashboard data for the technician
     */
    private function getDashboardData($teknisiId, $today)
    {
        // Service Tickets Statistics
        $totalTicketsToday = ServiceTicket::where('admin_id', $teknisiId)
            ->whereDate('schedule_date', $today)
            ->count();

        $pendingTickets = ServiceTicket::where('admin_id', $teknisiId)
            ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
            ->count();

        $completedTicketsToday = ServiceTicket::where('admin_id', $teknisiId)
            ->whereDate('updated_at', $today)
            ->where('status', 'Selesai')
            ->count();

        $overdueTickets = ServiceTicket::where('admin_id', $teknisiId)
            ->where('estimate_date', '<', $today)
            ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
            ->count();

        // Order Services Statistics
        $activeOrderServices = OrderService::whereHas('tickets', function ($query) use ($teknisiId) {
            $query->where('admin_id', $teknisiId);
        })
            ->whereNotIn('status_order', ['Selesai', 'Dibatalkan'])
            ->count();

        // Regular Queue Count
        $regularQueueCount = ServiceTicket::where('admin_id', $teknisiId)
            ->whereHas('orderService', function ($q) {
                $q->where('type', 'reguler');
            })
            ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
            ->count();

        // Notifications Count
        $unreadNotifications = SystemNotification::where('notifiable_id', $teknisiId)
            ->where('notifiable_type', 'App\Models\Admin')
            ->whereNull('read_at')
            ->count();

        // Weekly Performance
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();

        $weeklyCompleted = ServiceTicket::where('admin_id', $teknisiId)
            ->whereBetween('updated_at', [$weekStart, $weekEnd])
            ->where('status', 'Selesai')
            ->count();

        // Upcoming Schedule (next 3 days)
        $upcomingSchedules = ServiceTicket::where('admin_id', $teknisiId)
            ->whereBetween('schedule_date', [$today->copy()->addDay(), $today->copy()->addDays(3)])
            ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
            ->count();

        return [
            'todayTasks' => $totalTicketsToday,
            'completedTasks' => $completedTicketsToday,
            'pendingTasks' => $pendingTickets,
            'overdueSchedule' => $overdueTickets,
            'activeOrderServices' => $activeOrderServices,
            'regularQueueCount' => $regularQueueCount,
            'unreadNotifications' => $unreadNotifications,
            'weeklyCompleted' => $weeklyCompleted,
            'upcomingSchedules' => $upcomingSchedules,
            'averageRating' => 0, // TODO: Implement rating system
            'dashboardStats' => [
                'total_tickets_today' => $totalTicketsToday,
                'pending_tickets' => $pendingTickets,
                'completed_today' => $completedTicketsToday,
                'overdue_tickets' => $overdueTickets,
                'active_orders' => $activeOrderServices,
                'regular_queue' => $regularQueueCount,
                'unread_notifications' => $unreadNotifications,
                'weekly_performance' => $weeklyCompleted,
                'upcoming_schedules' => $upcomingSchedules,
            ]
        ];
    }

    /**
     * Get dashboard statistics for AJAX requests
     */
    public function getStats()
    {
        $teknisiId = Auth::guard('teknisi')->id();
        $today = Carbon::today();

        $stats = $this->getDashboardData($teknisiId, $today);

        return response()->json([
            'success' => true,
            'data' => $stats['dashboardStats'],
            'timestamp' => Carbon::now()->toISOString()
        ]);
    }

    /**
     * Get quick overview for mobile or compact view
     */
    public function getQuickOverview()
    {
        $teknisiId = Auth::guard('teknisi')->id();
        $today = Carbon::today();

        $overview = [
            'today_schedule' => ServiceTicket::where('admin_id', $teknisiId)
                ->whereDate('schedule_date', $today)
                ->count(),
            'pending_work' => ServiceTicket::where('admin_id', $teknisiId)
                ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
                ->count(),
            'urgent_tasks' => ServiceTicket::where('admin_id', $teknisiId)
                ->where('estimate_date', '<', $today)
                ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
                ->count(),
            'notifications' => SystemNotification::where('notifiable_id', $teknisiId)
                ->where('notifiable_type', 'App\Models\Admin')
                ->whereNull('read_at')
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $overview,
            'timestamp' => Carbon::now()->toISOString()
        ]);
    }
}
