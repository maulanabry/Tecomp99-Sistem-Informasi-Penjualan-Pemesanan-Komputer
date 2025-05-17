<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Models\Brand;

class BrandTable extends Component
{
    use WithPagination;

    public $isModalOpen = false;
    public $selectedBrandId = null;
    public $search = '';
    public $typeFilter = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;
    public $deleteId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc']
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
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

    #[Computed]
    public function isModalOpen()
    {
        return $this->isModalOpen;
    }

    public function confirmDelete($brandId = null)
    {
        if ($brandId === null) {
            session()->flash('error', 'ID brand tidak diberikan.');
            return;
        }

        $brand = Brand::find($brandId);
        if ($brand) {
            $this->selectedBrandId = $brandId;
            $this->isModalOpen = true;
        } else {
            session()->flash('error', 'Brand tidak ditemukan.');
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->selectedBrandId = null;
    }

    public function deleteBrand($brandId = null)
    {
        try {
            $brand = Brand::find($brandId);
            if (!$brand) {
                session()->flash('error', 'Brand tidak ditemukan.');
                return;
            }

            $brand->delete();
            $this->isModalOpen = false;
            $this->selectedBrandId = null;
            $this->deleteId = null;
            session()->flash('success', 'Brand berhasil dihapus.');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus brand.');
        }
    }

    public function render()
    {
        $brands = Brand::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.brand-table', [
            'brands' => $brands
        ]);
    }
}
