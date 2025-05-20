<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Service;

class ServiceTable extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $sortField = 'updated_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    public $isModalOpen = false;
    public $selectedServiceId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'sortField' => ['except' => 'updated_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
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

    public function confirmDelete($serviceId = null)
    {
        if ($serviceId === null) {
            session()->flash('error', 'ID servis tidak diberikan.');
            return;
        }

        $service = Service::find($serviceId);
        if ($service) {
            $this->selectedServiceId = $serviceId;
            $this->isModalOpen = true;
        } else {
            session()->flash('error', 'Servis tidak ditemukan.');
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->selectedServiceId = null;
    }

    public function deleteService($serviceId = null)
    {
        try {
            $service = Service::find($serviceId);
            if (!$service) {
                session()->flash('error', 'Servis tidak ditemukan.');
                return;
            }

            $service->delete();
            $this->isModalOpen = false;
            $this->selectedServiceId = null;
            session()->flash('success', 'Servis berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus servis.');
        }
    }

    public function render()
    {
        $services = Service::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('categories_id', $this->categoryFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.service-table', [
            'services' => $services,
        ]);
    }
}
