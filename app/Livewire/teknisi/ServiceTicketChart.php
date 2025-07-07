<?php

namespace App\Livewire\Teknisi;

use App\Models\ServiceTicket;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ServiceTicketChart extends Component
{
    public $period = 30; // Default 30 days
    public $chartData = [];
    public $chartLabels = [];

    public function mount()
    {
        $this->loadChartData();
    }

    public function setPeriod($days)
    {
        $this->period = $days;
        $this->loadChartData();
    }

    public function loadChartData()
    {
        $teknisiId = Auth::guard('teknisi')->id();
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays($this->period - 1);

        // Get service tickets data for the period
        $tickets = ServiceTicket::where('admin_id', $teknisiId)
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Generate complete date range with data
        $this->chartLabels = [];
        $this->chartData = [];

        $current = $startDate->copy();
        while ($current <= $endDate) {
            $dateKey = $current->format('Y-m-d');
            $this->chartLabels[] = $current->format('d/m');
            $this->chartData[] = $tickets->get($dateKey)->count ?? 0;
            $current->addDay();
        }
    }

    public function getStatistics()
    {
        $teknisiId = Auth::guard('teknisi')->id();
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays($this->period - 1);

        $totalTickets = ServiceTicket::where('admin_id', $teknisiId)
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->count();

        $averagePerDay = $totalTickets > 0 ? round($totalTickets / $this->period, 1) : 0;

        $maxDay = max($this->chartData);
        $maxDayIndex = array_search($maxDay, $this->chartData);
        $maxDayDate = $maxDayIndex !== false ? $this->chartLabels[$maxDayIndex] : '-';

        $completedTickets = ServiceTicket::where('admin_id', $teknisiId)
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->where('status', 'Selesai')
            ->count();

        $completionRate = $totalTickets > 0 ? round(($completedTickets / $totalTickets) * 100, 1) : 0;

        return [
            'total_tickets' => $totalTickets,
            'average_per_day' => $averagePerDay,
            'max_day' => $maxDay,
            'max_day_date' => $maxDayDate,
            'completion_rate' => $completionRate,
            'period_text' => $this->period . ' hari terakhir'
        ];
    }

    public function render()
    {
        return view('livewire.teknisi.service-ticket-chart', [
            'statistics' => $this->getStatistics(),
            'chartData' => $this->chartData,
            'chartLabels' => $this->chartLabels,
        ]);
    }
}
