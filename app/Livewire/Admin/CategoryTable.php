<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;

class CategoryTable extends Component
{
    use WithPagination;
    public $isModalOpen = false;
    public $selectedCategoryId = null;
    public $categoryActionRoute = null;
    public $search = '';
    public $categoryToDelete = null;
    public $typeFilter = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 10;

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

    protected $listeners = ['deleteConfirmed' => 'deleteCategory'];


    public function confirmDelete($categoryId)
    {
        $this->dispatchBrowserEvent('open-delete-modal', ['categoryId' => $categoryId]);
    }

    public function deleteCategory($itemId = null)
    {
        if (!$itemId) {
            session()->flash('error', 'ID Kategori tidak valid.');
            return;
        }

        try {
            $category = Category::find($itemId);
            if (!$category) {
                session()->flash('error', 'Kategori tidak ditemukan.');
                return;
            }
            $category->delete();

            session()->flash('message', 'Kategori berhasil dihapus.');
            $this->categoryToDelete = null;

            // Emit event to refresh other components if needed
            $this->dispatch('categoryDeleted');
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menghapus kategori.');
        }
    }

    public function render()
    {
        $categories = Category::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.admin.category-table', [
            'categories' => $categories
        ]);
    }
}
