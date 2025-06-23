<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Service;

use App\Models\Category;

class ServiceCardList extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 8;
    public $categoryFilter = '';

    protected $paginationTheme = 'tailwind';

    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $categories = Category::where('type', 'layanan')->get();

        $services = Service::where('is_active', true)
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->when($this->categoryFilter, function ($query) {
                $query->where('categories_id', $this->categoryFilter);
            })
            ->paginate($this->perPage);

        return view('livewire.admin.service-card-list', [
            'services' => $services,
            'categories' => $categories,
        ]);
    }
}
