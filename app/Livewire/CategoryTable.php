<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;

class CategoryTable extends Component
{
    use WithPagination;

    public $search = '';
    public $type = '';

    protected $paginationTheme = 'tailwind';

    protected $queryString = ['search', 'type'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingType()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Category::query();

        if ($this->search !== '') {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->type !== '') {
            $query->where('type', $this->type);
        }

        $categories = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.category-table', [
            'categories' => $categories,
        ]);
    }
}
