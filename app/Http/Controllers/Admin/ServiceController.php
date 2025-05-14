<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::with(['category' => function ($query) {
            $query->withTrashed();
        }]);
        $deletedQuery = Service::with(['category' => function ($query) {
            $query->withTrashed();
        }])->onlyTrashed();

        // Filter active services by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('categories_id', $request->category);
        }

        // Sorting
        $allowedSorts = ['service_id', 'name', 'price', 'slug', 'updated_at'];
        $sort = $request->get('sort', 'updated_at');
        $direction = $request->get('direction', 'desc');

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'updated_at';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        // Filter deleted services by name
        if ($request->filled('recovery_search')) {
            $deletedQuery->where('name', 'like', '%' . $request->recovery_search . '%');
        }

        // Filter deleted services by category
        if ($request->filled('recovery_category')) {
            $deletedQuery->where('categories_id', $request->recovery_category);
        }

        $services = $query->orderBy($sort, $direction)->paginate(10)->appends([
            'search' => $request->search,
            'category' => $request->category,
            'sort' => $sort,
            'direction' => $direction,
        ]);

        $deletedServices = $deletedQuery
            ->orderBy('deleted_at', 'desc')
            ->paginate(10)
            ->appends([
                'recovery_search' => $request->recovery_search,
                'recovery_category' => $request->recovery_category
            ]);

        $categories = Category::where('type', 'layanan')->get();

        return view('admin.service', compact('services', 'deletedServices', 'categories', 'sort', 'direction'));
    }

    public function create()
    {
        $categories = Category::where('type', 'layanan')->get();
        return view('admin.service.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'categories_id' => 'required|exists:categories,categories_id',
                'name' => 'required|max:255',
                'description' => 'required',
                'price' => 'required|integer|min:0',
                'thumbnail' => 'nullable|image|max:2048',
            ]);

            // Get the category code (assuming it's stored in categories table)
            $category = Category::findOrFail($validated['categories_id']);
            $categoryCode = str_pad($category->categories_id, 3, '0', STR_PAD_LEFT);

            // Get the last service ID for this category
            $lastService = Service::where('service_id', 'like', "SVC{$categoryCode}%")
                ->orderBy('service_id', 'desc')
                ->first();

            // Generate new incremental ID
            if ($lastService) {
                $lastIncrement = intval(substr($lastService->service_id, -3));
                $newIncrement = str_pad($lastIncrement + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newIncrement = '001';
            }

            // Generate service ID
            $validated['service_id'] = "SVC{$categoryCode}{$newIncrement}";

            // Generate slug from name
            $validated['slug'] = Str::slug($validated['name']);

            // Handle thumbnail upload
            $uploadPath = public_path('images/service');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            if ($request->hasFile('thumbnail')) {
                $file = $request->file('thumbnail');
                $fileName = time() . '_' . $file->getClientOriginalName();

                if (!$file->move($uploadPath, $fileName)) {
                    throw new \Exception('Failed to upload thumbnail file');
                }

                $validated['thumbnail'] = 'images/service/' . $fileName;
            }

            Service::create($validated);

            return redirect()->route('services.index')
                ->with('success', 'Servis berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menambahkan servis: ' . $e->getMessage()]);
        }
    }

    public function edit(Service $service)
    {
        $categories = Category::where('type', 'layanan')->get();
        return view('admin.service.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        try {
            $validated = $request->validate([
                'categories_id' => 'required|exists:categories,categories_id',
                'name' => 'required|max:255',
                'description' => 'required',
                'price' => 'required|integer|min:0',
                'thumbnail' => 'nullable|image|max:2048',
            ]);

            // Generate new slug from name
            $validated['slug'] = Str::slug($validated['name']);

            // Handle thumbnail upload
            $uploadPath = public_path('images/service');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            if ($request->hasFile('thumbnail')) {
                // Delete old thumbnail if exists
                if ($service->thumbnail) {
                    $oldThumbnailPath = public_path($service->thumbnail);
                    if (file_exists($oldThumbnailPath)) {
                        unlink($oldThumbnailPath);
                    }
                }

                $file = $request->file('thumbnail');
                $fileName = time() . '_' . $file->getClientOriginalName();

                if (!$file->move($uploadPath, $fileName)) {
                    throw new \Exception('Failed to upload thumbnail file');
                }

                $validated['thumbnail'] = 'images/service/' . $fileName;
            }

            // Note: We don't update service_id as it should remain constant
            $service->update($validated);

            return redirect()->route('services.index')
                ->with('success', 'Servis berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui servis: ' . $e->getMessage()]);
        }
    }

    public function destroy(Service $service)
    {
        try {
            $service->delete();
            return redirect()->route('services.index')
                ->with('success', 'Servis berhasil dihapus sementara.');
        } catch (\Exception $e) {
            return redirect()->route('services.index')
                ->with('error', 'Gagal menghapus servis. Servis mungkin sedang digunakan.');
        }
    }

    public function restore($id)
    {
        try {
            $service = Service::onlyTrashed()->findOrFail($id);
            $service->restore();
            return back()
                ->with('success', 'Servis berhasil dipulihkan.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal memulihkan servis: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $service = Service::withTrashed()->findOrFail($id);
            if ($service->thumbnail) {
                $thumbnailPath = public_path($service->thumbnail);
                if (file_exists($thumbnailPath)) {
                    unlink($thumbnailPath);
                }
            }
            $service->forceDelete();
            return redirect()->route('services.recovery')
                ->with('success', 'Servis berhasil dihapus permanen.');
        } catch (\Exception $e) {
            return redirect()->route('services.recovery')
                ->with('error', 'Gagal menghapus servis secara permanen.');
        }
    }

    public function recovery()
    {
        $services = Service::with(['category' => function ($query) {
            $query->withTrashed();
        }])
            ->onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        $categories = Category::where('type', 'layanan')->get();

        return view('admin.service.recovery', compact('services', 'categories'));
    }

    public function show(Service $service)
    {
        return view('admin.service.show', compact('service'));
    }
}
