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

        // Filter by name
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

        $brands = $query->orderBy($sort, $direction)->paginate(10)->appends([
            'search' => $request->search,
            'sort' => $sort,
            'direction' => $direction,
        ]);

        return view('admin.brand', compact('brands', 'sort', 'direction'));
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
                'logo' => 'nullable|image|max:2048',
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
                    throw new \Exception('Failed to upload logo file');
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
                ->withErrors(['error' => 'Failed to create brand: ' . $e->getMessage()]);
        }
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
                'logo' => 'nullable|image|max:2048',
            ]);

            // Ensure the directory exists
            $uploadPath = public_path('images/brand');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            if ($request->hasFile('logo')) {
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
                    throw new \Exception('Failed to upload logo file');
                }

                $validated['logo'] = 'images/brand/' . $fileName;
            }

            // Generate slug if not provided
            if (empty($validated['slug'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            $brand->update($validated);

            return redirect()->route('brands.index')
                ->with('success', 'Brand berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update brand: ' . $e->getMessage()]);
        }
    }

    public function destroy(Brand $brand)
    {
        if ($brand->logo) {
            $oldLogoPath = public_path($brand->logo);
            if (file_exists($oldLogoPath)) {
                unlink($oldLogoPath);
            }
        }
        $brand->delete();

        try {
            if ($brand->logo) {
                $oldLogoPath = public_path($brand->logo);
                if (file_exists($oldLogoPath)) {
                    unlink($oldLogoPath);
                }
            }
            $brand->delete();

            return redirect()->route('brands.index')
                ->with('success', 'Brand berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('brands.index')
                ->with('error', 'Gagal menghapus brand. Brand mungkin sedang digunakan.');
        }
    }
}
