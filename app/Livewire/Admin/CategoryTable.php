//calling view
<livewire:counter />

<?php



use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;

class CategoryTable extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterType = '';

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Category::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterType) {
            $query->where('type', $this->filterType);
        }

        $categories = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('livewire.admin.category-table', [
            'categories' => $categories,
        ]);
    }
}
