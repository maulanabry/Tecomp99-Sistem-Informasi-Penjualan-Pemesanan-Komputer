<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\OrderService;
use App\Models\ServiceTicket;

class OwnerServiceSchedulesList extends Component
{
    public $schedules = [];

    public function mount()
    {
        $this->loadSchedules();
    }

    public function loadSchedules()
    {
        $this->schedules = OrderService::with(['customer', 'tickets', 'items.service'])
            ->whereIn('status_order', ['menunggu', 'dijadwalkan', 'diproses'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($order) {
                $ticket = $order->tickets->first();
                $serviceName = $order->items->first() && $order->items->first()->service ?
                    $order->items->first()->service->name : 'Servis';

                return [
                    'order_service_id' => $order->order_service_id,
                    'customer_name' => $order->customer->name ?? 'N/A',
                    'contact' => $order->customer->contact ?? 'N/A',
                    'address' => $order->customer->formatted_address ?? 'Alamat tidak tersedia',
                    'time' => $ticket && $ticket->scheduled_date ?
                        \Carbon\Carbon::parse($ticket->scheduled_date)->format('d/m H:i') : 'Belum dijadwalkan',
                    'service_name' => $serviceName,
                    'status' => $order->status_order,
                    'complaints' => $order->complaints ?? 'Tidak ada keluhan',
                ];
            });
    }

    public function render()
    {
        return view('livewire.owner.owner-service-schedules-list');
    }
}
