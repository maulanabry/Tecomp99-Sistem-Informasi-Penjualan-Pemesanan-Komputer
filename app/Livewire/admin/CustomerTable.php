<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;

class CustomerTable extends Component
{
    use WithPagination;

    public $search = '';
    public $hasAccountFilter = '';
    public $genderFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;

    protected $queryString = [
        'search' => ['except' => ''],
        'hasAccountFilter' => ['except' => ''],
        'genderFilter' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
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

    public function updatingGenderFilter()
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
            $this->sortDirection = 'asc';
        }

        $this->sortField = $field;
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->hasAccountFilter = '';
        $this->genderFilter = '';
        $this->resetPage();
    }

    public function render()
    {
        $query = Customer::query()->with(['defaultAddress']);

        // Search filter
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('contact', 'like', '%' . $this->search . '%')
                    ->orWhere('customer_id', 'like', '%' . $this->search . '%');
            });
        }

        // Account status filter
        if ($this->hasAccountFilter !== '') {
            $query->where('hasAccount', $this->hasAccountFilter);
        }

        // Gender filter
        if ($this->genderFilter !== '') {
            $query->where('gender', $this->genderFilter);
        }

        $customers = $query->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.customer-table', [
            'customers' => $customers,
        ]);
    }
}
