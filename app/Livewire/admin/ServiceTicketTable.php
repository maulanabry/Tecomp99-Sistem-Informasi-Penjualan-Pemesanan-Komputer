<?php

namespace App\Livewire\Admin;

use App\Models\ServiceTicket;
use Livewire\Component;
use Livewire\WithPagination;

class ServiceTicketTable extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'asc';
    public $activeTab = 'all';
    public $serviceTypeFilter = '';
    public $perPage = 10;

    public $selectedServiceTicketId = null;
    public $isCancelModalOpen = false;

    protected $listeners = ['refreshServiceTicketTable' => '$refresh'];

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

    public function updatingServiceTypeFilter()
    {
        $this->resetPage();
    }

    public function openCancelModal($serviceTicketId)
    {
        $this->selectedServiceTicketId = $serviceTicketId;
        $this->isCancelModalOpen = true;
    }

    public function closeCancelModal()
    {
        $this->selectedServiceTicketId = null;
        $this->isCancelModalOpen = false;
    }

    public function confirmCancelTicket()
    {
        if (!$this->selectedServiceTicketId) {
            session()->flash('error', 'Tiket servis tidak ditemukan.');
            return;
        }

        try {
            $serviceTicket = ServiceTicket::findOrFail($this->selectedServiceTicketId);

            if (!in_array($serviceTicket->status, ['Selesai', 'Dibatalkan'])) {
                $serviceTicket->update([
                    'status' => 'Dibatalkan'
                ]);

                session()->flash('success', 'Tiket servis berhasil dibatalkan.');
            } else {
                session()->flash('error', 'Tiket servis tidak dapat dibatalkan.');
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal membatalkan tiket servis.');
        }

        $this->closeCancelModal();
    }

    public function render()
    {
        return view('livewire.admin.service-ticket-table', [
            'tickets' => ServiceTicket::query()
                ->with(['orderService.customer']) // Eager load relationships
                ->when($this->search, function ($query) {
                    $query->where(function ($q) {
                        $q->where('service_ticket_id', 'like', '%' . $this->search . '%')
                            ->orWhere('order_service_id', 'like', '%' . $this->search . '%')
                            ->orWhereHas('orderService.customer', function ($q) {
                                $q->where('name', 'like', '%' . $this->search . '%');
                            });
                    });
                })
                ->when($this->activeTab !== 'all', function ($query) {
                    $query->where('status', $this->activeTab);
                })
                ->when($this->serviceTypeFilter, function ($query) {
                    $query->whereHas('orderService', function ($q) {
                        $q->where('type', $this->serviceTypeFilter);
                    });
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage)
        ]);
    }
}
