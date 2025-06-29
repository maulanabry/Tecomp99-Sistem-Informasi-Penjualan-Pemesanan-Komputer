<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\OrderProduct;
use Carbon\Carbon;

class ShippingOrderList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusPaymentFilter = '';
    public $timeFilter = 'today';
    public $activeTab = 'all';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusPaymentFilter' => ['except' => ''],
        'timeFilter' => ['except' => 'today'],
        'activeTab' => ['except' => 'all'],
    ];

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        $query = OrderProduct::with(['customer', 'customer.defaultAddress', 'shipping'])
            ->where('type', 'pengiriman')
            ->whereNotIn('status_order', ['selesai', 'dibatalkan']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('order_product_id', 'like', '%' . $this->search . '%')
                    ->orWhereHas('customer', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        if ($this->activeTab !== 'all') {
            $query->where('status_order', $this->activeTab);
        }

        if ($this->statusPaymentFilter) {
            $query->where('status_payment', $this->statusPaymentFilter);
        }

        switch ($this->timeFilter) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                break;
            case 'week':
                $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
                break;
        }

        $shippingOrders = $query->latest()->paginate(10);

        return view('livewire.admin.shipping-order-list', [
            'shippingOrders' => $shippingOrders
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusPaymentFilter()
    {
        $this->resetPage();
    }

    public function updatingTimeFilter()
    {
        $this->resetPage();
    }

    public function updatingActiveTab()
    {
        $this->resetPage();
    }
}
