<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductOverviewController extends Controller
{
    /**
     * Tampilkan halaman overview produk berdasarkan slug
     */
    public function show($slug)
    {
        // Cari produk berdasarkan slug dengan relasi yang diperlukan
        $product = Product::with(['category', 'brand', 'images'])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Data untuk breadcrumbs
        $breadcrumbs = [
            ['name' => 'Beranda', 'url' => route('home')],
            ['name' => 'Produk', 'url' => route('products.public')],
            ['name' => $product->name, 'url' => null, 'active' => true]
        ];

        return view('customer.produk-overview', compact('product', 'breadcrumbs'));
    }
}
