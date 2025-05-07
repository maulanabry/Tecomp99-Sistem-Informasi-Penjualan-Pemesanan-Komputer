<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();
        $deletedQuery = Category::onlyTrashed();

        // Filter active categories by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by type
        if ($request->filled('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        // Sorting
        $allowedSorts = ['name', 'type', 'slug', 'created_at'];
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        // Filter deleted categories by name
        if ($request->filled('recovery_search')) {
            $deletedQuery->where('name', 'like', '%' . $request->recovery_search . '%');
        }

        // Filter deleted categories by type
        if ($request->filled('recovery_type') && $request->recovery_type !== '') {
            $deletedQuery->where('type', $request->recovery_type);
        }

        $categories = $query->orderBy($sort, $direction)->paginate(15)->appends([
            'search' => $request->search,
            'type' => $request->type,
            'sort' => $sort,
            'direction' => $direction,
        ]);

        $deletedCategories = $deletedQuery
            ->orderBy('deleted_at', 'desc')
            ->paginate(10)
            ->appends([
                'recovery_search' => $request->recovery_search,
                'recovery_type' => $request->recovery_type,
            ]);

        return view('admin.category', compact('categories', 'deletedCategories', 'sort', 'direction'));
    }
    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'type' => 'required|in:produk,layanan',
            ]);

            $validated['slug'] = Str::slug($validated['name']);

            Category::create($validated);

            return redirect()->route('categories.index')
                ->with('success', 'Kategori berhasil ditambahkan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Gagal menambahkan kategori. Silakan periksa kembali data yang dimasukkan.');
        }
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'type' => 'required|in:produk,layanan',
            ]);

            $validated['slug'] = Str::slug($validated['name']);

            $category->update($validated);

            return redirect()->route('categories.index')
                ->with('success', 'Kategori berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Gagal memperbarui kategori. Silakan periksa kembali data yang dimasukkan.');
        }
    }

    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('categories.index')
                ->with('success', 'Kategori berhasil dihapus sementara.');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')
                ->with('error', 'Gagal menghapus kategori. Kategori mungkin sedang digunakan.');
        }
    }

    public function restore($id)
    {
        try {
            $category = Category::onlyTrashed()->findOrFail($id);
            $category->restore();
            return back()
                ->with('success', 'Kategori berhasil dipulihkan.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal memulihkan kategori: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $category = Category::withTrashed()->findOrFail($id);
            $category->forceDelete();
            return redirect()->route('categories.recovery')
                ->with('success', 'Kategori berhasil dihapus permanen.');
        } catch (\Exception $e) {
            return redirect()->route('categories.recovery')
                ->with('error', 'Gagal menghapus kategori secara permanen.');
        }
    }

    public function recovery()
    {
        $categories = Category::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(15);

        return view('admin.categories.recovery', compact('categories'));
    }
}
