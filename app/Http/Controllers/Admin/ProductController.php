<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images']);
        $deletedQuery = Product::with(['category', 'brand'])->onlyTrashed();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->where('categories_id', $request->category);
        }

        $allowedSorts = ['product_id', 'name', 'price', 'stock', 'sold_count', 'is_active', 'updated_at'];
        $sort = $request->get('sort', 'updated_at');
        $direction = $request->get('direction', 'desc');

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'updated_at';
        }
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        if ($request->filled('recovery_search')) {
            $deletedQuery->where('name', 'like', '%' . $request->recovery_search . '%');
        }

        if ($request->filled('recovery_category')) {
            $deletedQuery->where('categories_id', $request->recovery_category);
        }

        $products = $query->orderBy($sort, $direction)->paginate(10)->appends([
            'search' => $request->search,
            'category' => $request->category,
            'sort' => $sort,
            'direction' => $direction,
        ]);

        $deletedProducts = $deletedQuery
            ->orderBy('deleted_at', 'desc')
            ->paginate(10)
            ->appends([
                'recovery_search' => $request->recovery_search,
                'recovery_category' => $request->recovery_category
            ]);

        $categories = Category::where('type', 'produk')->get();
        $brands = Brand::all();

        return view('admin.product', compact('products', 'deletedProducts', 'categories', 'brands', 'sort', 'direction'));
    }

    public function create()
    {
        $categories = Category::where('type', 'produk')->get();
        $brands = Brand::all();
        return view('admin.product.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'categories_id' => 'required|exists:categories,categories_id',
                'brand_id' => 'required|exists:brands,brand_id',
                'name' => 'required|max:255',
                'description' => 'nullable',
                'price' => 'required|integer|min:0',
                'stock' => 'required|integer|min:0',
                'is_active' => 'boolean',
                'images' => 'required|array|min:1|max:6',
                'images.*' => 'image|max:2048', // 2MB limit per image
            ]);

            // Get the category code
            $category = Category::findOrFail($validated['categories_id']);
            $categoryCode = str_pad($category->categories_id, 3, '0', STR_PAD_LEFT);

            // Get the last product ID for this category
            $lastProduct = Product::where('product_id', 'like', "PRD{$categoryCode}%")
                ->withTrashed()
                ->orderBy('product_id', 'desc')
                ->first();

            // Generate new incremental ID
            if ($lastProduct) {
                $lastIncrement = intval(substr($lastProduct->product_id, -3));
                $newIncrement = str_pad($lastIncrement + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newIncrement = '001';
            }

            // Generate product ID
            $productId = "PRD{$categoryCode}{$newIncrement}";
            $validated['product_id'] = $productId;
            $validated['slug'] = Str::slug($validated['name']);

            // Create product folder
            $productFolder = "images/products/{$productId}";
            $uploadPath = public_path($productFolder);
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Create product
            $product = Product::create($validated);

            // Handle multiple image uploads
            if ($request->hasFile('images')) {
                $images = $request->file('images');
                foreach ($images as $index => $image) {
                    $extension = $image->getClientOriginalExtension();
                    $fileName = "{$productId}-img-" . ($index + 1) . ".{$extension}";

                    if ($image->move($uploadPath, $fileName)) {
                        $product->images()->create([
                            'url' => "{$productFolder}/{$fileName}",
                            'is_main' => $index === 0 // First image is main
                        ]);
                    }
                }
            }

            return redirect()->route('products.index')
                ->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menambahkan produk: ' . $e->getMessage()]);
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::where('type', 'produk')->get();
        $brands = Brand::all();
        return view('admin.product.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'categories_id' => 'required|exists:categories,categories_id',
                'brand_id' => 'required|exists:brands,brand_id',
                'name' => 'required|max:255',
                'description' => 'nullable',
                'price' => 'required|integer|min:0',
                'stock' => 'required|integer|min:0',
                'is_active' => 'boolean',
                'images' => 'nullable|array|max:6',
                'images.*' => 'image|max:2048',
            ]);

            // Generate new slug from name
            $validated['slug'] = Str::slug($validated['name']);

            // Product folder path
            $productFolder = "images/products/{$product->product_id}";
            $uploadPath = public_path($productFolder);
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Handle multiple image uploads
            if ($request->hasFile('images')) {
                $currentImageCount = $product->images()->count();
                $newImages = $request->file('images');

                // Check if total images would exceed limit
                if ($currentImageCount + count($newImages) > 6) {
                    throw new \Exception('Maximum 6 images allowed per product.');
                }

                foreach ($newImages as $index => $image) {
                    $extension = $image->getClientOriginalExtension();
                    $fileName = "{$product->product_id}-img-" . ($currentImageCount + $index + 1) . ".{$extension}";

                    if ($image->move($uploadPath, $fileName)) {
                        $product->images()->create([
                            'url' => "{$productFolder}/{$fileName}",
                            'is_main' => $currentImageCount === 0 && $index === 0 // First image is main only if no existing images
                        ]);
                    }
                }
            }

            $product->update($validated);

            return redirect()->route('products.index')
                ->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal memperbarui produk: ' . $e->getMessage()]);
        }
    }

    public function show(Product $product)
    {
        return view('admin.product.show', compact('product'));
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('products.index')
                ->with('success', 'Produk berhasil dihapus sementara.');
        } catch (\Exception $e) {
            return redirect()->route('products.index')
                ->with('error', 'Gagal menghapus produk.');
        }
    }

    public function restore($id)
    {
        try {
            $product = Product::onlyTrashed()->findOrFail($id);
            $product->restore();
            return back()
                ->with('success', 'Produk berhasil dipulihkan.');
        } catch (\Exception $e) {
            return back()
                ->with('error', 'Gagal memulihkan produk: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $product = Product::withTrashed()->findOrFail($id);

            // Delete associated images and folder
            foreach ($product->images as $image) {
                $imagePath = public_path($image->url);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Remove product folder if it exists
            $productFolder = public_path("images/products/{$product->product_id}");
            if (file_exists($productFolder)) {
                rmdir($productFolder);
            }

            $product->forceDelete();
            return redirect()->route('products.recovery')
                ->with('success', 'Produk berhasil dihapus permanen.');
        } catch (\Exception $e) {
            return redirect()->route('products.recovery')
                ->with('error', 'Gagal menghapus produk secara permanen.');
        }
    }

    public function recovery()
    {
        $products = Product::with(['category', 'brand'])
            ->onlyTrashed()
            ->orderBy('deleted_at', 'desc')
            ->paginate(10);

        $categories = Category::where('type', 'produk')->get();

        return view('admin.product.recovery', compact('products', 'categories'));
    }

    public function deleteImage($productId, $imageId)
    {
        try {
            $product = Product::findOrFail($productId);
            $image = $product->images()->findOrFail($imageId);

            // Delete the image file
            $imagePath = public_path($image->url);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // If this was the main image, set the next available image as main
            if ($image->is_main) {
                $nextImage = $product->images()->where('id', '!=', $imageId)->first();
                if ($nextImage) {
                    $nextImage->update(['is_main' => true]);
                }
            }

            $image->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function setMainImage($productId, $imageId)
    {
        try {
            $product = Product::findOrFail($productId);

            // Remove main flag from all product images
            $product->images()->update(['is_main' => false]);

            // Set the selected image as main
            $image = $product->images()->findOrFail($imageId);
            $image->update(['is_main' => true]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
