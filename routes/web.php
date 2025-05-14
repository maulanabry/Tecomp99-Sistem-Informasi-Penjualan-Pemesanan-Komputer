<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Pemilik\PemilikDashboardController;
use App\Http\Controllers\Pemilik\TeknisiDashboardController as PemilikTeknisiDashboardController;
use App\Http\Controllers\Teknisi\DashboardController as TeknisiDashboardController;
use App\Http\Controllers\Teknisi\TeknisiDashboardController as TeknisiTeknisiDashboardController;
use Illuminate\Support\Facades\Route;

// ==========================
// Authentication Routes
// ==========================
Route::get('/login', [AuthController::class, 'index'])
    ->name('login')
    ->middleware('guest:admin,teknisi,pemilik');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ==========================
// Protected Routes
// ==========================
Route::middleware('auth:admin,teknisi,pemilik')->group(function () {
    // Categories
    Route::prefix('admin/kategori')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::get('/recovery', [\App\Http\Controllers\Admin\CategoryController::class, 'recovery'])->name('categories.recovery');
        Route::get('/create', [\App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('categories.create');
        Route::post('/', [\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::get('/{category}/edit', [\App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/{category}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::post('/{id}/restore', [\App\Http\Controllers\Admin\CategoryController::class, 'restore'])->name('categories.restore');
        Route::delete('/{id}/force', [\App\Http\Controllers\Admin\CategoryController::class, 'forceDelete'])->name('categories.force-delete');
    });

    // Brands
    Route::prefix('admin/brand')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\BrandController::class, 'index'])->name('brands.index');
        Route::get('/recovery', [\App\Http\Controllers\Admin\BrandController::class, 'recovery'])->name('brands.recovery');
        Route::get('/create', [\App\Http\Controllers\Admin\BrandController::class, 'create'])->name('brands.create');
        Route::post('/', [\App\Http\Controllers\Admin\BrandController::class, 'store'])->name('brands.store');
        Route::get('/{brand}/edit', [\App\Http\Controllers\Admin\BrandController::class, 'edit'])->name('brands.edit');
        Route::put('/{brand}', [\App\Http\Controllers\Admin\BrandController::class, 'update'])->name('brands.update');
        Route::delete('/{brand}', [\App\Http\Controllers\Admin\BrandController::class, 'destroy'])->name('brands.destroy');
        Route::post('/{id}/restore', [\App\Http\Controllers\Admin\BrandController::class, 'restore'])->name('brands.restore');
        Route::delete('/{id}/force', [\App\Http\Controllers\Admin\BrandController::class, 'forceDelete'])->name('brands.force-delete');
    });

    // Services
    Route::prefix('admin/servis')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ServiceController::class, 'index'])->name('services.index');
        Route::get('/recovery', [\App\Http\Controllers\Admin\ServiceController::class, 'recovery'])->name('services.recovery');
        Route::get('/create', [\App\Http\Controllers\Admin\ServiceController::class, 'create'])->name('services.create');
        Route::post('/', [\App\Http\Controllers\Admin\ServiceController::class, 'store'])->name('services.store');
        Route::get('/{service}/edit', [\App\Http\Controllers\Admin\ServiceController::class, 'edit'])->name('services.edit');
        Route::put('/{service}', [\App\Http\Controllers\Admin\ServiceController::class, 'update'])->name('services.update');
        Route::delete('/{service}', [\App\Http\Controllers\Admin\ServiceController::class, 'destroy'])->name('services.destroy');
        Route::post('/{id}/restore', [\App\Http\Controllers\Admin\ServiceController::class, 'restore'])->name('services.restore');
        Route::delete('/{id}/force', [\App\Http\Controllers\Admin\ServiceController::class, 'forceDelete'])->name('services.force-delete');
    });

    // Products
    Route::prefix('admin/produk')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('products.index');
        Route::get('/recovery', [\App\Http\Controllers\Admin\ProductController::class, 'recovery'])->name('products.recovery');
        Route::get('/create', [\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('products.create');
        Route::post('/', [\App\Http\Controllers\Admin\ProductController::class, 'store'])->name('products.store');
        Route::get('/{product}/edit', [\App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('products.edit');
        Route::put('/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'update'])->name('products.update');
        Route::delete('/{product}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/{id}/restore', [\App\Http\Controllers\Admin\ProductController::class, 'restore'])->name('products.restore');
        Route::delete('/{id}/force', [\App\Http\Controllers\Admin\ProductController::class, 'forceDelete'])->name('products.force-delete');

        // New image management routes
        Route::delete('/{productId}/images/{imageId}', [\App\Http\Controllers\Admin\ProductController::class, 'deleteImage'])->name('products.images.delete');
        Route::post('/{productId}/images/{imageId}/main', [\App\Http\Controllers\Admin\ProductController::class, 'setMainImage'])->name('products.images.main');
    });

    // Promos
    Route::prefix('admin/promo')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PromoController::class, 'index'])->name('promos.index');
        Route::get('/recovery', [\App\Http\Controllers\Admin\PromoController::class, 'recovery'])->name('promos.recovery');
        Route::get('/create', [\App\Http\Controllers\Admin\PromoController::class, 'create'])->name('promos.create');
        Route::post('/', [\App\Http\Controllers\Admin\PromoController::class, 'store'])->name('promos.store');
        Route::get('/{promo}/edit', [\App\Http\Controllers\Admin\PromoController::class, 'edit'])->name('promos.edit');
        Route::put('/{promo}', [\App\Http\Controllers\Admin\PromoController::class, 'update'])->name('promos.update');
        Route::delete('/{promo}', [\App\Http\Controllers\Admin\PromoController::class, 'destroy'])->name('promos.destroy');
        Route::post('/{id}/restore', [\App\Http\Controllers\Admin\PromoController::class, 'restore'])->name('promos.restore');
        Route::delete('/{id}/force', [\App\Http\Controllers\Admin\PromoController::class, 'forceDelete'])->name('promos.force-delete');
    });

    // Customers
    Route::prefix('admin/pelanggan')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CustomerController::class, 'index'])->name('customers.index');
        Route::get('/recovery', [\App\Http\Controllers\Admin\CustomerController::class, 'recovery'])->name('customers.recovery');
        Route::get('/create', [\App\Http\Controllers\Admin\CustomerController::class, 'create'])->name('customers.create');
        Route::post('/', [\App\Http\Controllers\Admin\CustomerController::class, 'store'])->name('customers.store');
        Route::get('/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'show'])->name('customers.show');
        Route::get('/{customer}/edit', [\App\Http\Controllers\Admin\CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'update'])->name('customers.update');
        Route::delete('/{customer}', [\App\Http\Controllers\Admin\CustomerController::class, 'destroy'])->name('customers.destroy');
        Route::post('/{id}/restore', [\App\Http\Controllers\Admin\CustomerController::class, 'restore'])->name('customers.restore');
        Route::delete('/{id}/force', [\App\Http\Controllers\Admin\CustomerController::class, 'forceDelete'])->name('customers.force-delete');
    });

    // Order
    Route::get('/order/servis', function () {
        return view('order.servis');
    })->name('order.servis');
    Route::get('/order/produk', function () {
        return view('order.produk');
    })->name('order.produk');

    // Transaksi
    Route::get('/transaksi', function () {
        return view('transaksi');
    })->name('transaksi');

    // Jadwal
    Route::get('/jadwal', function () {
        return view('jadwal');
    })->name('jadwal');

    // Peraturan
    Route::get('/peraturan', function () {
        return view('peraturan');
    })->name('peraturan');
});

// ==========================
// Role-Specific Dashboards
// ==========================

// Admin Dashboard
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard.index');
});

// Teknisi Dashboard
Route::middleware('auth:teknisi')->group(function () {
    Route::get('/teknisi/dashboard', [TeknisiTeknisiDashboardController::class, 'index'])->name('teknisi.dashboard.index');
});

// Pemilik Dashboard
Route::middleware('auth:pemilik')->group(function () {
    Route::get('/pemilik/dashboard', [PemilikDashboardController::class, 'index'])->name('pemilik.dashboard.index');
});
