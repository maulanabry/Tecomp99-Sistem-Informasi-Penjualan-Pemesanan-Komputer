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
    public $serviceTypeFilter = '';
    public $statusFilter = '';
    public $perPage = 10;

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

    public function updatingServiceTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
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
                ->when($this->serviceTypeFilter, function ($query) {
                    $query->whereHas('orderService', function ($q) {
                        $q->where('type', $this->serviceTypeFilter);
                    });
                })
                ->when($this->statusFilter, function ($query) {
                    $query->where('status', $this->statusFilter);
                })
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage)
        ]);
    }
}
