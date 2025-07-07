<?php

namespace App\Livewire\Teknisi;

use App\Models\ServiceTicket;
use App\Models\OrderService;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardSummaryCards extends Component
{
    public $compactView = false;

    /**
     * Toggle between compact and full view
     */
    public function toggleView()
    {
        $this->compactView = !$this->compactView;
    }

    /**
     * Get dashboard summary data for technician
     */
    public function getSummaryData()
    {
        $teknisiId = Auth::guard('teknisi')->id();
        $today = Carbon::today();

        return [
            'total_tiket_hari_ini' => ServiceTicket::where('admin_id', $teknisiId)
                ->whereDate('schedule_date', $today)
                ->count(),

            'tiket_belum_selesai' => ServiceTicket::where('admin_id', $teknisiId)
                ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
                ->count(),

            'order_servis_aktif' => OrderService::whereHas('tickets', function ($query) use ($teknisiId) {
                $query->where('admin_id', $teknisiId);
            })
                ->whereNotIn('status_order', ['Selesai', 'Dibatalkan'])
                ->count(),

            'overdue_schedule' => ServiceTicket::where('admin_id', $teknisiId)
                ->where('estimate_date', '<', $today)
                ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
                ->count(),
        ];
    }

    /**
     * Navigate to filtered service tickets
     */
    public function navigateToTickets($filter = null)
    {
        $route = route('teknisi.service-tickets.index');

        if ($filter) {
            $route .= '?filter=' . $filter;
        }

        return redirect($route);
    }

    /**
     * Navigate to order services
     */
    public function navigateToOrderServices()
    {
        return redirect()->route('teknisi.order-services.index');
    }

    public function render()
    {
        return view('livewire.teknisi.dashboard-summary-cards', [
            'summaryData' => $this->getSummaryData()
        ]);
    }
}
