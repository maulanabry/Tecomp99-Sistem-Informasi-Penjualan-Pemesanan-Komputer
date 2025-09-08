<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\OrderProduct;
use App\Models\OrderService;
use Illuminate\Support\Collection;

class OrderSelectionModal extends Component
{
    use WithPagination;

    public $show = false;
    public $searchQuery = '';
    public $orderTypeFilter = '';
    public $statusFilter = '';
    public $selectedOrder = null;

    protected $listeners = ['openOrderModal' => 'open'];

    public function mount()
    {
        $this->resetPage();
    }

    public function updatedSearchQuery()
    {
        $this->resetPage();
    }

    public function updatedOrderTypeFilter()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function open()
    {
        $this->show = true;
    }

    public function close()
    {
        $this->show = false;
        $this->reset(['searchQuery', 'orderTypeFilter', 'statusFilter']);
        $this->resetPage();
    }

    public function selectOrder($orderId, $orderType)
    {
        if ($orderType === 'produk') {
            $order = OrderProduct::with('customer')->find($orderId);
            if ($order) {
                $this->selectedOrder = [
                    'id' => $order->order_product_id,
                    'type' => 'produk',
                    'customer_name' => $order->customer->name,
                    'customer_id' => $order->customer_id,
                    'sub_total' => $order->sub_total,
                    'discount_amount' => $order->discount_amount ?? 0,
                    'grand_total' => $order->grand_total,
                    'paid_amount' => $order->paid_amount ?? 0,
                    'remaining_balance' => $order->remaining_balance ?? $order->grand_total,
                    'status_order' => $order->status_order,
                    'status_payment' => $order->status_payment,
                    'last_payment_at' => $order->last_payment_at,
                    'created_at' => $order->created_at,
                    'shipping_cost' => $order->shipping_cost ?? 0,
                    'order_type_display' => 'Produk',
                ];
            }
        } else {
            $order = OrderService::with('customer')->find($orderId);
            if ($order) {
                $this->selectedOrder = [
                    'id' => $order->order_service_id,
                    'type' => 'servis',
                    'customer_name' => $order->customer->name,
                    'customer_id' => $order->customer_id,
                    'sub_total' => $order->sub_total,
                    'discount_amount' => $order->discount_amount ?? 0,
                    'grand_total' => $order->grand_total,
                    'paid_amount' => $order->paid_amount ?? 0,
                    'remaining_balance' => $order->remaining_balance ?? $order->grand_total,
                    'status_order' => $order->status_order,
                    'status_payment' => $order->status_payment,
                    'last_payment_at' => $order->last_payment_at,
                    'created_at' => $order->created_at,
                    'device' => $order->device,
                    'order_type_display' => 'Servis',
                ];
            }
        }

        if ($this->selectedOrder) {
            // Dispatch event ke parent component dengan data order
            $this->dispatch('orderSelected', $this->selectedOrder);
        }

        $this->close();
    }

    public function getOrdersProperty()
    {
        $orders = collect();

        // Ambil order produk
        if (empty($this->orderTypeFilter) || $this->orderTypeFilter === 'produk') {
            $productOrders = OrderProduct::with('customer')
                ->when($this->searchQuery, function ($query) {
                    $query->where(function ($q) {
                        $q->where('order_product_id', 'like', '%' . $this->searchQuery . '%')
                            ->orWhereHas('customer', function ($customerQuery) {
                                $customerQuery->where('name', 'like', '%' . $this->searchQuery . '%');
                            })
                            ->orWhereDate('created_at', 'like', '%' . $this->searchQuery . '%');
                    });
                })
                ->when($this->statusFilter, function ($query) {
                    if ($this->statusFilter === 'payment_status') {
                        // Filter berdasarkan status pembayaran
                        return $query;
                    } else {
                        $query->where('status_payment', $this->statusFilter);
                    }
                })
                // Hanya tampilkan order yang bisa menerima pembayaran
                ->whereNotIn('status_payment', ['lunas', 'dibatalkan'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($order) {
                    return [
                        'id' => $order->order_product_id,
                        'type' => 'produk',
                        'customer_name' => $order->customer->name,
                        'customer_id' => $order->customer_id,
                        'sub_total' => $order->sub_total,
                        'discount_amount' => $order->discount_amount ?? 0,
                        'grand_total' => $order->grand_total,
                        'paid_amount' => $order->paid_amount ?? 0,
                        'remaining_balance' => $order->remaining_balance ?? $order->grand_total,
                        'status_order' => $order->status_order,
                        'status_payment' => $order->status_payment,
                        'last_payment_at' => $order->last_payment_at,
                        'created_at' => $order->created_at,
                        'shipping_cost' => $order->shipping_cost ?? 0,
                        'order_type_display' => 'Produk',
                    ];
                });

            $orders = $orders->merge($productOrders);
        }

        // Ambil order servis
        if (empty($this->orderTypeFilter) || $this->orderTypeFilter === 'servis') {
            $serviceOrders = OrderService::with('customer')
                ->when($this->searchQuery, function ($query) {
                    $query->where(function ($q) {
                        $q->where('order_service_id', 'like', '%' . $this->searchQuery . '%')
                            ->orWhereHas('customer', function ($customerQuery) {
                                $customerQuery->where('name', 'like', '%' . $this->searchQuery . '%');
                            })
                            ->orWhereDate('created_at', 'like', '%' . $this->searchQuery . '%');
                    });
                })
                ->when($this->statusFilter, function ($query) {
                    if ($this->statusFilter === 'payment_status') {
                        // Filter berdasarkan status pembayaran
                        return $query;
                    } else {
                        $query->where('status_payment', $this->statusFilter);
                    }
                })
                // Hanya tampilkan order yang bisa menerima pembayaran
                ->whereNotIn('status_payment', ['lunas', 'dibatalkan'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($order) {
                    return [
                        'id' => $order->order_service_id,
                        'type' => 'servis',
                        'customer_name' => $order->customer->name,
                        'customer_id' => $order->customer_id,
                        'sub_total' => $order->sub_total,
                        'discount_amount' => $order->discount_amount ?? 0,
                        'grand_total' => $order->grand_total,
                        'paid_amount' => $order->paid_amount ?? 0,
                        'remaining_balance' => $order->remaining_balance ?? $order->grand_total,
                        'status_order' => $order->status_order,
                        'status_payment' => $order->status_payment,
                        'last_payment_at' => $order->last_payment_at,
                        'created_at' => $order->created_at,
                        'device' => $order->device ?? '',
                        'order_type_display' => 'Servis',
                    ];
                });

            $orders = $orders->merge($serviceOrders);
        }

        // Sort by created_at desc dan paginate
        $orders = $orders->sortByDesc('created_at');

        // Manual pagination untuk collection
        $perPage = 10;
        $currentPage = $this->getPage();
        $currentItems = $orders->slice(($currentPage - 1) * $perPage, $perPage)->values();

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $orders->count(),
            $perPage,
            $currentPage,
            [
                'path' => request()->url(),
                'pageName' => 'page',
            ]
        );
    }

    public function render()
    {
        return view('livewire.admin.order-selection-modal', [
            'orders' => $this->orders,
        ]);
    }
}
