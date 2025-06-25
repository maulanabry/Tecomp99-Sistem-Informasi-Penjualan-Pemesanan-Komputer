<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::query();
        $deletedQuery = Brand::onlyTrashed();

        // Filter active brands by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sorting
        $allowedSorts = ['name', 'created_at'];
        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'created_at';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        // Filter deleted brands by name
        if ($request->filled('recovery_search')) {
            $deletedQuery->where('name', 'like', '%' . $request->recovery_search . '%');
        }

        $brands = $query->orderBy($sort, $direction)->paginate(10)->appends([
            'search' => $request->search,
            'sort' => $sort,
            'direction' => $direction,
        ]);

        $deletedBrands = $deletedQuery
            ->orderBy('deleted_at', 'desc')
            ->paginate(10)
            ->appends([
                'recovery_search' => $request->recovery_search
            ]);

        return view('admin.brand', compact('brands', 'deletedBrands', 'sort', 'direction'));
    }

    public function create()
    {
        return view('admin.brand.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'slug' => 'required|max:255|unique:brands',
                'logo' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            ], [
                'name.required' => 'Nama brand wajib diisi.',
                'name.max' => 'Nama brand tidak boleh lebih dari 255 karakter.',
                'slug.required' => 'Slug wajib diisi.',
                'slug.max' => 'Slug tidak boleh lebih dari 255 karakter.',
                'slug.unique' => 'Slug sudah digunakan oleh brand lain.',
                'logo.image' => 'File harus berupa gambar.',
                'logo.mimes' => 'Format gambar harus JPEG, PNG, atau WebP.',
                'logo.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
            ]);

            // Ensure the directory exists
            $uploadPath = public_path('images/brand');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $fileName = time() . '_' . $file->getClientOriginalName();

                // Move the file
                if (!$file->move($uploadPath, $fileName)) {
                    throw new \Exception('Gagal mengunggah file logo');
                }

                $validated['logo'] = 'images/brand/' . $fileName;
            }

            // Generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            Brand::create($validated);

            return redirect()->route('brands.index')
                ->with('success', 'Brand berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal membuat brand: ' . $e->getMessage()]);
        }
    }

    public function show(Brand $brand)
    {
        return view('admin.brand.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('admin.brand.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|max:255',
                'slug' => 'required|max:255|unique:brands,slug,' . $brand->brand_id . ',brand_id',
                'logo' => 'nullable|image|mimes:jpeg,png,webp|max:2048',
                'remove_logo' => 'nullable|boolean',
            ], [
                'name.required' => 'Nama brand wajib diisi.',
                'name.max' => 'Nama brand tidak boleh lebih dari 255 karakter.',
                'slug.required' => 'Slug wajib diisi.',
                'slug.max' => 'Slug tidak boleh lebih dari 255 karakter.',
                'slug.unique' => 'Slug sudah digunakan oleh brand lain.',
                'logo.image' => 'File harus berupa gambar.',
                'logo.mimes' => 'Format gambar harus JPEG, PNG, atau WebP.',
                'logo.max' => 'Ukuran file tidak boleh lebih dari 2MB.',
            ]);

            // Ensure the directory exists
            $uploadPath = public_path('images/brand');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Handle logo removal
            if ($request->input('remove_logo') == '1') {
                if ($brand->logo) {
                    $oldLogoPath = public_path($brand->logo);
                    if (file_exists($oldLogoPath)) {
                        unlink($oldLogoPath);
                    }
                }
                $validated['logo'] = null;
            }
            // Handle new logo upload
            elseif ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($brand->logo) {
                    $oldLogoPath = public_path($brand->logo);
                    if (file_exists($oldLogoPath)) {
                        unlink($oldLogoPath);
                    }
                }

                $file = $request->file('logo');
                $fileName = time() . '_' . $file->getClientOriginalName();

                // Move the file
                if (!$file->move($uploadPath, $fileName)) {
                    throw new \Exception('Gagal mengunggah file logo');
                }

                $validated['logo'] = 'images/brand/' . $fileName;
            }

            // Generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            // Remove remove_logo from validated data before updating
            unset($validated['remove_logo']);

            $brand->update($validated);

            return redirect()->route('brands.index')
                ->with('success', 'Brand berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui brand: ' . $e->getMessage()]);
        }
    }

    public function destroy(Brand $brand)
    {
        try {
            $brand->delete();
            return redirect()->route('brands.index')
                ->with('success', 'Brand berhasil dihapus sementara.');
        } catch (\Exception $e) {
            return redirect()->route('brands.index')
                ->with('error', 'Gagal menghapus brand. Brand mungkin sedang digunakan.');
        }
    }

    public function restore($id)
    {
        try {
            $brand = Brand::onlyTrashed()->findOrFail($id);
            $brand->restore();
            return back()
                ->with('success', 'Brand berhasil dipulihkan.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal memulihkan brand: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $brand = Brand::withTrashed()->findOrFail($id);
            if ($brand->logo) {
                $oldLogoPath = public_path($brand->logo);
                if (file_exists($oldLogoPath)) {
                    unlink($oldLogoPath);
                }
            }
            $brand->forceDelete();
            return redirect()->route('brands.recovery')
                ->with('success', 'Brand berhasil dihapus permanen.');
        } catch (\Exception $e) {
            return redirect()->route('brands.recovery')
                ->with('error', 'Gagal menghapus brand secara permanen.');
        }
    }

    public function recovery()
    {
        $brands = Brand::onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        return view('admin.brand.recovery', compact('brands'));
    }
}
