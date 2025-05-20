<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Customer;

class CustomerTable extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $selectedCustomerId = null;
    public $search = '';
    public $hasAccountFilter = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'hasAccountFilter' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
        'perPage' => ['except' => 10],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingHasAccountFilter()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Removed 

    // public function confirmDelete($customerId = null)
    // {
    //     if ($customerId === null) {
    //         session()->flash('error', 'ID pelanggan tidak diberikan.');
    //         return;
    //     }

    //     $customer = Customer::find($customerId);
    //     if ($customer) {
    //         $this->selectedCustomerId = $customerId;
    //         $this->isModalOpen = true;
    //     } else {
    //         session()->flash('error', 'Pelanggan tidak ditemukan.');
    //     }
    // }

    // public function closeModal()
    // {
    //     $this->isModalOpen = false;
    //     $this->selectedCustomerId = null;
    // }

    // public function deleteCustomer($customerId = null)
    // {
    //     try {
    //         $customer = Customer::find($customerId);
    //         if (!$customer) {
    //             session()->flash('error', 'Pelanggan tidak ditemukan.');
    //             return;
    //         }

    //         $customer->delete();
    //         $this->isModalOpen = false;
    //         $this->selectedCustomerId = null;
    //         $this->resetPage();
    //         session()->flash('success', 'Pelanggan berhasil dihapus.');
    //     } catch (\Exception $e) {
    //         session()->flash('error', 'Gagal menghapus pelanggan.');
    //     }
    // }

    public function render()
    {
        $customers = Customer::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->hasAccountFilter !== '', function ($query) {
                $query->where('hasAccount', (int) $this->hasAccountFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.customer-table', [
            'customers' => $customers
        ]);
    }
}
