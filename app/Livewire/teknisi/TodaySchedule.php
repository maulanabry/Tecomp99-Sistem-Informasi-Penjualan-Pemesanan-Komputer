<?php

namespace App\Livewire\Teknisi;

use App\Models\ServiceTicket;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TodaySchedule extends Component
{
    public $showAll = false;
    public $limit = 5;

    /**
     * Toggle show all schedules
     */
    public function toggleShowAll()
    {
        $this->showAll = !$this->showAll;
    }

    /**
     * Get today's visit schedules for the technician
     */
    public function getTodaySchedules()
    {
        $teknisiId = Auth::guard('teknisi')->id();
        $today = Carbon::today();

        $query = ServiceTicket::with(['orderService.customer.addresses'])
            ->where('admin_id', $teknisiId)
            ->whereDate('schedule_date', $today)
            ->orderBy('visit_schedule', 'asc');

        if (!$this->showAll) {
            $query->limit($this->limit);
        }

        return $query->get()->map(function ($ticket) {
            return [
                'ticket_id' => $ticket->service_ticket_id,
                'customer_name' => $ticket->orderService->customer->name ?? 'N/A',
                'customer_phone' => $ticket->orderService->customer->phone ?? 'N/A',
                'alamat' => $this->getCustomerAddress($ticket),
                'jam_kunjungan' => $ticket->visit_schedule ? Carbon::parse($ticket->visit_schedule)->format('H:i') : 'Belum dijadwalkan',
                'layanan' => $this->getServiceDescription($ticket),
                'status' => $ticket->status,
                'status_badge' => $this->getStatusBadge($ticket),
                'order_type' => $ticket->orderService->type,
                'device' => $ticket->orderService->device ?? 'N/A',
                'complaints' => $ticket->orderService->complaints ?? 'N/A',
            ];
        });
    }

    /**
     * Get customer address for onsite services
     */
    private function getCustomerAddress($ticket)
    {
        if ($ticket->orderService->type === 'onsite') {
            $customer = $ticket->orderService->customer;
            if ($customer && $customer->addresses->isNotEmpty()) {
                $address = $customer->addresses->first();
                return $address->detail_address ?? 'Alamat detail belum tersedia';
            }
            return 'Alamat belum tersedia';
        }
        return 'Servis di toko';
    }

    /**
     * Get service description
     */
    private function getServiceDescription($ticket)
    {
        $device = $ticket->orderService->device ?? 'Perangkat';
        $type = $ticket->orderService->type === 'onsite' ? 'Kunjungan' : 'Reguler';
        return $device . ' (' . $type . ')';
    }

    /**
     * Get status badge configuration
     */
    private function getStatusBadge($ticket)
    {
        $now = Carbon::now();
        $visitTime = $ticket->visit_schedule ? Carbon::parse($ticket->visit_schedule) : null;

        switch ($ticket->status) {
            case 'Selesai':
                return ['class' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300', 'text' => 'Selesai'];
            case 'Diproses':
                return ['class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300', 'text' => 'Sedang Diproses'];
            case 'Menunggu':
                if ($visitTime && $visitTime->isPast()) {
                    return ['class' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300', 'text' => 'Terlambat'];
                } elseif ($visitTime && $visitTime->diffInMinutes($now) <= 30 && $visitTime->isFuture()) {
                    return ['class' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300', 'text' => 'Segera Dimulai'];
                }
                return ['class' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300', 'text' => 'Menunggu'];
            default:
                return ['class' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300', 'text' => $ticket->status];
        }
    }

    /**
     * Start service for a ticket
     */
    public function startService($ticketId)
    {
        return redirect()->route('teknisi.service-tickets.show', $ticketId);
    }

    /**
     * View ticket details
     */
    public function viewTicket($ticketId)
    {
        return redirect()->route('teknisi.service-tickets.show', $ticketId);
    }

    public function render()
    {
        $schedules = $this->getTodaySchedules();
        $totalSchedules = ServiceTicket::where('admin_id', Auth::guard('teknisi')->id())
            ->whereDate('schedule_date', Carbon::today())
            ->count();

        return view('livewire.teknisi.today-schedule', [
            'schedules' => $schedules,
            'totalSchedules' => $totalSchedules,
            'hasMore' => $totalSchedules > $this->limit && !$this->showAll
        ]);
    }
}
