<?php

namespace App\Livewire\Teknisi;

use App\Models\ServiceTicket;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RegularQueue extends Component
{
    public $showAll = false;
    public $limit = 5;

    /**
     * Toggle show all queue items
     */
    public function toggleShowAll()
    {
        $this->showAll = !$this->showAll;
    }

    /**
     * Get regular queue (in-store customers) for the technician
     */
    public function getRegularQueue()
    {
        $teknisiId = Auth::guard('teknisi')->id();

        $query = ServiceTicket::with(['orderService.customer'])
            ->where('admin_id', $teknisiId)
            ->whereHas('orderService', function ($q) {
                $q->where('type', 'reguler');
            })
            ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
            ->orderBy('created_at', 'asc'); // First-come, first-served

        if (!$this->showAll) {
            $query->limit($this->limit);
        }

        return $query->get()->map(function ($ticket, $index) {
            $waitingHours = Carbon::parse($ticket->created_at)->diffInHours(Carbon::now());
            return [
                'ticket_id' => $ticket->service_ticket_id,
                'urutan' => $index + 1,
                'customer_name' => $ticket->orderService->customer->name ?? 'N/A',
                'customer_phone' => $ticket->orderService->customer->phone ?? 'N/A',
                'tanggal_order' => Carbon::parse($ticket->orderService->created_at)->format('d/m/Y'),
                'waktu_order' => Carbon::parse($ticket->orderService->created_at)->format('H:i'),
                'device' => $ticket->orderService->device ?? 'N/A',
                'complaints' => $ticket->orderService->complaints ?? 'N/A',
                'status' => $ticket->status,
                'status_badge' => $this->getStatusBadge($ticket),
                'waiting_time' => $this->getWaitingTime($ticket),
                'priority' => $this->getPriority($ticket),
                'is_overdue' => $waitingHours > 24,
            ];
        });
    }

    /**
     * Get status badge configuration
     */
    private function getStatusBadge($ticket)
    {
        switch ($ticket->status) {
            case 'Menunggu':
                return ['class' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300', 'text' => 'Menunggu Antrian'];
            case 'Diproses':
                return ['class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300', 'text' => 'Sedang Diproses'];
            case 'Perlu Diambil':
                return ['class' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300', 'text' => 'Siap Diambil'];
            default:
                return ['class' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300', 'text' => $ticket->status];
        }
    }

    /**
     * Calculate waiting time
     */
    private function getWaitingTime($ticket)
    {
        $created = Carbon::parse($ticket->created_at);
        $now = Carbon::now();

        $diffInDays = (int) $created->diffInDays($now);
        $diffInHours = (int) ($created->diffInHours($now) % 24);
        $diffInMinutes = (int) ($created->diffInMinutes($now) % 60);

        $waitingTime = [];

        if ($diffInDays > 0) {
            $waitingTime[] = $diffInDays . ' hari';
        }

        if ($diffInHours > 0) {
            $waitingTime[] = $diffInHours . ' jam';
        }

        if ($diffInMinutes > 0 && $diffInDays == 0) {
            $waitingTime[] = $diffInMinutes . ' menit';
        }

        if (empty($waitingTime)) {
            return 'Baru saja';
        }

        return implode(' ', $waitingTime);
    }

    /**
     * Get priority level based on waiting time and device type
     */
    private function getPriority($ticket)
    {
        $waitingHours = Carbon::parse($ticket->created_at)->diffInHours(Carbon::now());

        if ($waitingHours > 24) {
            return ['level' => 'high', 'text' => 'Tinggi', 'class' => 'text-red-600 dark:text-red-400'];
        } elseif ($waitingHours > 8) {
            return ['level' => 'medium', 'text' => 'Sedang', 'class' => 'text-yellow-600 dark:text-yellow-400'];
        }
        return ['level' => 'normal', 'text' => 'Normal', 'class' => 'text-green-600 dark:text-green-400'];
    }

    /**
     * Start processing a ticket
     */
    public function startProcessing($ticketId)
    {
        $ticket = ServiceTicket::find($ticketId);
        if ($ticket && $ticket->admin_id == Auth::guard('teknisi')->id()) {
            $ticket->update(['status' => 'Diproses']);
            session()->flash('message', 'Tiket berhasil dimulai untuk diproses.');
        }

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
        $queue = $this->getRegularQueue();
        $totalQueue = ServiceTicket::where('admin_id', Auth::guard('teknisi')->id())
            ->whereHas('orderService', function ($q) {
                $q->where('type', 'reguler');
            })
            ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
            ->count();

        return view('livewire.teknisi.regular-queue', [
            'queue' => $queue,
            'totalQueue' => $totalQueue,
            'hasMore' => $totalQueue > $this->limit && !$this->showAll
        ]);
    }
}
