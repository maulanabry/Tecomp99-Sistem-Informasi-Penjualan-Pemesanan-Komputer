<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\OrderService;
use Illuminate\Support\Collection;

class ServiceTicketOrderSelectionModal extends Component
{
    use WithPagination;

    public $show = false;
    public $searchQuery = '';
    public $typeFilter = '';
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';
    public $selectedOrder = null;

    // Properties untuk pre-selection order dari halaman detail
    public $preSelectedOrder;

    protected $listeners = ['openServiceTicketOrderModal' => 'open', 'openModal' => 'open'];

    public function mount()
    {
        $this->resetPage();
    }

    public function updatedSearchQuery()
    {
        $this->resetPage();
    }

    public function updatedTypeFilter()
    {
        $this->resetPage();
    }

    public function open()
    {
        \Log::info('ServiceTicketOrderSelectionModal: Opening modal');
        $this->show = true;
        $this->reset(['searchQuery', 'typeFilter', 'selectedOrder']);
        $this->resetPage();
        \Log::info('ServiceTicketOrderSelectionModal: Modal opened successfully');
    }

    public function close()
    {
        $this->show = false;
        $this->reset(['searchQuery', 'typeFilter', 'selectedOrder']);
        $this->resetPage();
    }

    public function selectOrder($orderId)
    {
        \Log::info('ServiceTicketOrderSelectionModal: Selecting order', ['orderId' => $orderId]);

        $order = OrderService::with('customer')->find($orderId);
        if ($order) {
            $orderData = [
                'id' => $order->order_service_id,
                'customer_name' => $order->customer->name ?? '',
                'customer_id' => $order->customer_id ?? '',
                'device' => $order->device ?? '',
                'complaints' => $order->complaints ?? '',
                'type' => $order->type ?? 'reguler',
                'status_order' => $order->status_order ?? '',
                'created_at' => $order->created_at,
            ];

            \Log::info('ServiceTicketOrderSelectionModal: Dispatching order data', $orderData);

            // Kirim event ke parent component dengan data order sebagai object
            $this->dispatch('serviceTicketOrderSelected', $orderData);
        } else {
            \Log::error('ServiceTicketOrderSelectionModal: Order not found', ['orderId' => $orderId]);
        }

        $this->close();
    }

    public function sortBy($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'desc';
        }
        $this->resetPage();
    }

    public function getOrdersProperty()
    {
        $query = OrderService::with('customer')
            ->where('hasTicket', false) // Hanya order yang belum memiliki tiket
            ->when($this->searchQuery, function ($query) {
                $query->where(function ($q) {
                    $q->where('order_service_id', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('device', 'like', '%' . $this->searchQuery . '%')
                        ->orWhere('complaints', 'like', '%' . $this->searchQuery . '%')
                        ->orWhereHas('customer', function ($customerQuery) {
                            $customerQuery->where('name', 'like', '%' . $this->searchQuery . '%');
                        });
                });
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.service-ticket-order-selection-modal', [
            'orders' => $this->orders,
        ]);
    }
}
