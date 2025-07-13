<?php

namespace App\Livewire\Public;

use App\Models\Category;
use Livewire\Component;

class CategoriesSection extends Component
{
    public $categories;

    public function mount()
    {
        // Ambil semua kategori aktif
        $this->categories = Category::whereHas('products', function ($query) {
            $query->where('is_active', true);
        })
            ->orWhereHas('services', function ($query) {
                $query->where('is_active', true);
            })
            ->limit(8)
            ->get();
    }

    public function render()
    {
        return view('livewire.public.categories-section');
    }
}
