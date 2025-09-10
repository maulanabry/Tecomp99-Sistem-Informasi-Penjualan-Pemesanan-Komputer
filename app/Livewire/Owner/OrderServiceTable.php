<?php

namespace App\Livewire\Owner;

use App\Models\OrderService;
use Livewire\Component;
use Livewire\WithPagination;

class OrderServiceTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $activeTab = 'all';
    public $statusPaymentFilter = '';
    public $typeFilter = '';
    public $perPage = 10;

    public $selectedOrderServiceId = null;
    public $isCancelModalOpen = false;

    protected $listeners = ['orderServiceSummaryToggled' => 'render'];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function updatingStatusPaymentFilter()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function openCancelModal($orderServiceId)
    {
        $this->selectedOrderServiceId = $orderServiceId;
        $this->isCancelModalOpen = true;
    }

    public function closeCancelModal()
    {
        $this->selectedOrderServiceId = null;
        $this->isCancelModalOpen = false;
    }

    public function confirmCancelOrder()
    {
        if (!$this->selectedOrderServiceId) {
            session()->flash('error', 'Order servis tidak ditemukan.');
            return;
        }

        try {
            $orderService = OrderService::findOrFail($this->selectedOrderServiceId);

            if ($orderService->status_order !== 'Selesai' && $orderService->status_payment !== 'lunas') {
                $orderService->update([
                    'status_order' => 'Dibatalkan',
                    'status_payment' => 'dibatalkan'
                ]);

                session()->flash('success', 'Order servis berhasil dibatalkan.');
            } else {
                session()->flash('error', 'Order servis tidak dapat dibatalkan.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membatalkan order servis.');
        }

        $this->closeCancelModal();
    }

    public function render()
    {
        // Get status counts
        $statusCounts = OrderService::selectRaw('status_order, COUNT(*) as count')
            ->groupBy('status_order')
            ->pluck('count', 'status_order')
            ->toArray();

        // Ensure all status tabs have a count (default to 0 if not present)
        $allStatuses = [
            'Menunggu' => 0,
            'Diproses' => 0,
            'Selesai' => 0,
            'Dibatalkan' => 0,
        ];

        $statusCounts = array_merge($allStatuses, $statusCounts);
        $totalCount = array_sum($statusCounts);

        return view('livewire.owner.order-service-table', [
            'orderServices' => OrderService::with('customer')
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('order_service_id', 'like', '%' . $this->search . '%')
                            ->orWhere('device', 'like', '%' . $this->search . '%')
                            ->orWhereHas('customer', function ($q) {
                                $q->where('name', 'like', '%' . $this->search . '%')
                                    ->orWhere('email', 'like', '%' . $this->search . '%')
                                    ->orWhere('contact', 'like', '%' . $this->search . '%');
                            });
                    });
                })
                ->when($this->activeTab !== 'all', function ($query) {
                    $query->where('status_order', $this->activeTab);
                })
                ->when($this->statusPaymentFilter, function ($query) {
                    $query->where('status_payment', $this->statusPaymentFilter);
                })
                ->when($this->typeFilter, function ($query) {
                    $query->where('type', $this->typeFilter);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage),
            'statusCounts' => $statusCounts,
            'totalCount' => $totalCount
        ]);
    }
}
