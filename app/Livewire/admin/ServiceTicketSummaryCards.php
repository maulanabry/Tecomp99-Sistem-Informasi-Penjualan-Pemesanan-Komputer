<?php

namespace App\Livewire\Admin;

use App\Models\ServiceTicket;
use Livewire\Component;
use Carbon\Carbon;

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
     * Mendapatkan data ringkasan tiket servis
     */
    public function getSummaries()
    {
        return [
            'total' => ServiceTicket::count(),
            'today' => ServiceTicket::whereDate('schedule_date', Carbon::today())->count(),
            'waiting' => ServiceTicket::where('status', 'Menunggu')->count(),
            'processing' => ServiceTicket::where('status', 'Diproses')->count(),
            'onLocation' => ServiceTicket::where('status', 'Menuju Lokasi')->count(),
            'completed' => ServiceTicket::where('status', 'Selesai')->count(),
            'cancelled' => ServiceTicket::onlyTrashed()->count(),
            'overdue' => ServiceTicket::where('estimate_date', '<', Carbon::today())
                ->where('status', '!=', 'Selesai')
                ->count(),
        ];
    }

    public function render()
    {
        return view('livewire.admin.service-ticket-summary-cards', [
            'summaries' => $this->getSummaries()
        ]);
    }
}
