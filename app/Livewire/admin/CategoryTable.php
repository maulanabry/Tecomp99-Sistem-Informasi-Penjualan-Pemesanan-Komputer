<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
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

    public function confirmDelete($categoryId = null)
    {
        if ($categoryId === null) {
            session()->flash('error', 'ID kategori tidak diberikan.');
            return;
        }

        $category = Category::find($categoryId);
        if ($category) {
            $this->selectedCategoryId = $categoryId;
            $this->isModalOpen = true;
        } else {
            session()->flash('error', 'Kategori tidak ditemukan.');
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->selectedCategoryId = null;
    }

    public function deleteCategory($categoryId = null)
    {
        try {
            $category = Category::find($categoryId);
            if (!$category) {
                session()->flash('error', 'Kategori tidak ditemukan.');
                return;
            }

            $category->delete();
            $this->isModalOpen = false;
            $this->selectedCategoryId = null;
            $this->deleteId = null;
            session()->flash('success', 'Kategori berhasil dihapus.');
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
