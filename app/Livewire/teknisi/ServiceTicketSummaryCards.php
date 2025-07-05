<?php

namespace App\Livewire\Teknisi;

use App\Models\ServiceTicket;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ServiceTicketSummaryCards extends Component
{
    public $showAllCards = false;

    /**
     * Toggle tampilan kartu
     */
    public function toggleCards()
    {
        $this->showAllCards = !$this->showAllCards;
    }

    /**
     * Mendapatkan data ringkasan tiket servis untuk teknisi
     */
    public function getSummaries()
    {
        $teknisiId = Auth::guard('teknisi')->id();

        return [
            'total' => ServiceTicket::where('admin_id', $teknisiId)->count(),
            'today' => ServiceTicket::where('admin_id', $teknisiId)
                ->whereDate('schedule_date', Carbon::today())->count(),
            'waiting' => ServiceTicket::where('admin_id', $teknisiId)
                ->where('status', 'Menunggu')->count(),
            'processing' => ServiceTicket::where('admin_id', $teknisiId)
                ->where('status', 'Diproses')->count(),
            'delivered' => ServiceTicket::where('admin_id', $teknisiId)
                ->where('status', 'Diantar')->count(),
            'pickup' => ServiceTicket::where('admin_id', $teknisiId)
                ->where('status', 'Perlu Diambil')->count(),
            'completed' => ServiceTicket::where('admin_id', $teknisiId)
                ->where('status', 'Selesai')->count(),
            'cancelled' => ServiceTicket::where('admin_id', $teknisiId)
                ->where('status', 'Dibatalkan')->count(),
            'overdue' => ServiceTicket::where('admin_id', $teknisiId)
                ->where('estimate_date', '<', Carbon::today())
                ->where('status', '!=', 'Selesai')
                ->where('status', '!=', 'Dibatalkan')
                ->count(),
        ];
    }

    public function render()
    {
        return view('livewire.teknisi.service-ticket-summary-cards', [
            'summaries' => $this->getSummaries()
        ]);
    }
}
