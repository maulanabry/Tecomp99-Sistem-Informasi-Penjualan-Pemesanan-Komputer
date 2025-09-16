<?php

namespace App\Livewire\Owner;

use Livewire\Component;
use App\Models\OrderService;
use Carbon\Carbon;

class OwnerOverdueOrdersList extends Component
{
    public $overdueOrders = [];

    public function mount()
    {
        $this->loadOverdueOrders();
    }

    public function loadOverdueOrders()
    {
        $now = Carbon::now();

        $this->overdueOrders = OrderService::with(['customer.addresses', 'items.service'])
            ->where('expired_date', '<', $now)
            ->where('status_order', '!=', 'selesai')
            ->orderBy('expired_date', 'asc')
            ->take(5)
            ->get()
            ->map(function ($order) use ($now) {
                $daysOverdue = $now->diffInDays($order->expired_date);

                $primaryAddress = $order->customer->addresses->first();
                $address = $primaryAddress ? $primaryAddress->detail_address : 'Alamat tidak tersedia';

                $serviceNames = $order->items->map(function ($item) {
                    return $item->service->name ?? 'Layanan tidak ditemukan';
                })->take(2)->implode(', ');

                if ($order->items->count() > 2) {
                    $serviceNames .= '...';
                }

                return [
                    'order_code' => $order->order_service_id,
                    'customer_name' => $order->customer->name ?? 'N/A',
                    'customer_contact' => $order->customer->phone ?? 'N/A',
                    'address' => $address,
                    'services' => $serviceNames,
                    'deadline' => $order->expired_date ? $order->expired_date->format('d M Y') : 'N/A',
                    'days_overdue' => $daysOverdue,
                    'status' => $order->status_order,
                    'amount' => $order->grand_total,
                ];
            });
    }

    public function render()
    {
        return view('livewire.owner.owner-overdue-orders-list');
    }
}
