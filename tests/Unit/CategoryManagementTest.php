<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Test Unit untuk Manajemen Kategori Produk
 * 
 * Test ini mencakup user story:
 * - Sebagai admin, saya ingin mengelola kategori produk sehingga dapat mengorganisir produk dengan lebih baik
 */
class CategoryManagementTest extends TestCase
{
    use DatabaseTransactions;

    private $categoryData;

    /**
     * Setup yang dijalankan sebelum setiap test
     * Menyiapkan data kategori untuk testing
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Data kategori untuk testing
        $this->categoryData = [
            'name' => 'Elektronik',
            'type' => 'produk',
            'slug' => 'elektronik'
        ];
    }

    /**
     * Test membuat kategori baru
     * 
     * Skenario:
     * 1. Buat kategori baru dengan data valid
     * 2. Verifikasi kategori tersimpan di database
     * 3. Verifikasi slug otomatis ter-generate jika kosong
     */
    public function test_admin_dapat_membuat_kategori_baru()
    {
        // Act: Buat kategori baru
        $category = Category::create($this->categoryData);

        // Assert: Verifikasi kategori tersimpan
        $this->assertInstanceOf(Category::class, $category, 'Harus mengembalikan instance Category');
        $this->assertDatabaseHas('categories', [
            'name' => 'Elektronik',
            'type' => 'produk',
            'slug' => 'elektronik'
        ]);

        // Verifikasi primary key ter-set
        $this->assertNotNull($category->categories_id, 'Primary key categories_id harus ter-set');
    }

    /**
     * Test auto-generate slug saat membuat kategori
     * 
     * Skenario:
     * 1. Buat kategori tanpa slug
     * 2. Verifikasi slug otomatis ter-generate dari nama
     */
    public function test_slug_otomatis_tergenerate_dari_nama_kategori()
    {
        // Arrange: Data kategori tanpa slug
        $categoryDataWithoutSlug = [
            'name' => 'Komputer & Laptop',
            'type' => 'produk'
        ];

        // Act: Buat kategori tanpa slug
        $category = Category::create($categoryDataWithoutSlug);

        // Assert: Verifikasi slug ter-generate otomatis
        $this->assertEquals('komputer-laptop', $category->slug, 'Slug harus ter-generate otomatis dari nama');
        $this->assertDatabaseHas('categories', [
            'name' => 'Komputer & Laptop',
            'slug' => 'komputer-laptop'
        ]);
    }

    /**
     * Test membaca/menampilkan data kategori
     * 
     * Skenario:
     * 1. Buat beberapa kategori di database
     * 2. Ambil semua kategori
     * 3. Verifikasi data kategori yang diambil
     */
    public function test_admin_dapat_membaca_data_kategori()
    {
        // Arrange: Buat beberapa kategori
        $category1 = Category::create([
            'name' => 'Elektronik',
            'type' => 'produk',
            'slug' => 'elektronik'
        ]);

        $category2 = Category::create([
            'name' => 'Perbaikan Laptop',
            'type' => 'layanan',
            'slug' => 'perbaikan-laptop'
        ]);

        // Act: Ambil semua kategori
        $categories = Category::all();

        // Assert: Verifikasi data kategori
        $this->assertGreaterThan(0, $categories->count(), 'Harus ada kategori');

        // Verifikasi kategori pertama
        $foundCategory1 = $categories->where('name', 'Elektronik')->first();
        $this->assertNotNull($foundCategory1, 'Kategori Elektronik harus ditemukan');
        $this->assertEquals('produk', $foundCategory1->type, 'Type kategori harus produk');

        // Verifikasi kategori kedua
        $foundCategory2 = $categories->where('name', 'Perbaikan Laptop')->first();
        $this->assertNotNull($foundCategory2, 'Kategori Perbaikan Laptop harus ditemukan');
        $this->assertEquals('layanan', $foundCategory2->type, 'Type kategori harus layanan');
    }

    /**
     * Test mengupdate data kategori
     * 
     * Skenario:
     * 1. Buat kategori baru
     * 2. Update data kategori
     * 3. Verifikasi perubahan tersimpan di database
     */
    public function test_admin_dapat_mengupdate_kategori()
    {
        // Arrange: Buat kategori
        $category = Category::create($this->categoryData);

        // Act: Update kategori
        $updatedData = [
            'name' => 'Elektronik & Gadget',
            'type' => 'produk',
            'slug' => 'elektronik-gadget'
        ];
        $category->update($updatedData);

        // Assert: Verifikasi perubahan tersimpan
        $category->refresh();
        $this->assertEquals('Elektronik & Gadget', $category->name, 'Nama kategori harus terupdate');
        $this->assertEquals('elektronik-gadget', $category->slug, 'Slug kategori harus terupdate');

        // Verifikasi di database
        $this->assertDatabaseHas('categories', [
            'categories_id' => $category->categories_id,
            'name' => 'Elektronik & Gadget',
            'slug' => 'elektronik-gadget'
        ]);
    }

    /**
     * Test soft delete kategori
     * 
     * Skenario:
     * 1. Buat kategori baru
     * 2. Hapus kategori (soft delete)
     * 3. Verifikasi kategori tidak muncul di query normal
     * 4. Verifikasi kategori masih ada dengan withTrashed()
     */
    public function test_admin_dapat_menghapus_kategori_soft_delete()
    {
        // Arrange: Buat kategori
        $category = Category::create($this->categoryData);
        $categoryId = $category->categories_id;

        // Act: Hapus kategori (soft delete)
        $category->delete();

        // Assert: Verifikasi kategori tidak muncul di query normal
        $this->assertNull(Category::find($categoryId), 'Kategori tidak boleh ditemukan setelah dihapus');

        // Verifikasi kategori masih ada dengan withTrashed()
        $trashedCategory = Category::withTrashed()->find($categoryId);
        $this->assertNotNull($trashedCategory, 'Kategori harus masih ada dengan withTrashed()');
        $this->assertNotNull($trashedCategory->deleted_at, 'deleted_at harus ter-set');
    }

    /**
     * Test restore kategori yang sudah dihapus
     * 
     * Skenario:
     * 1. Buat dan hapus kategori
     * 2. Restore kategori
     * 3. Verifikasi kategori kembali muncul di query normal
     */
    public function test_admin_dapat_restore_kategori_yang_dihapus()
    {
        // Arrange: Buat dan hapus kategori
        $category = Category::create($this->categoryData);
        $categoryId = $category->categories_id;
        $category->delete();

        // Verifikasi kategori terhapus
        $this->assertNull(Category::find($categoryId), 'Kategori harus terhapus');

        // Act: Restore kategori
        $trashedCategory = Category::withTrashed()->find($categoryId);
        $trashedCategory->restore();

        // Assert: Verifikasi kategori kembali muncul
        $restoredCategory = Category::find($categoryId);
        $this->assertNotNull($restoredCategory, 'Kategori harus kembali muncul setelah restore');
        $this->assertNull($restoredCategory->deleted_at, 'deleted_at harus null setelah restore');
        $this->assertEquals('Elektronik', $restoredCategory->name, 'Data kategori harus sama setelah restore');
    }

    /**
     * Test force delete kategori (hapus permanen)
     * 
     * Skenario:
     * 1. Buat dan hapus kategori
     * 2. Force delete kategori
     * 3. Verifikasi kategori benar-benar hilang dari database
     */
    public function test_admin_dapat_force_delete_kategori()
    {
        // Arrange: Buat dan hapus kategori
        $category = Category::create($this->categoryData);
        $categoryId = $category->categories_id;
        $category->delete();

        // Act: Force delete kategori
        $trashedCategory = Category::withTrashed()->find($categoryId);
        $trashedCategory->forceDelete();

        // Assert: Verifikasi kategori benar-benar hilang
        $this->assertNull(Category::withTrashed()->find($categoryId), 'Kategori harus benar-benar hilang setelah force delete');
        $this->assertDatabaseMissing('categories', [
            'categories_id' => $categoryId
        ]);
    }

    /**
     * Test relasi kategori dengan produk
     * 
     * Skenario:
     * 1. Buat kategori produk
     * 2. Buat produk yang terkait dengan kategori
     * 3. Verifikasi relasi kategori-produk berfungsi
     */
    public function test_relasi_kategori_dengan_produk()
    {
        // Arrange: Buat kategori produk
        $category = Category::create([
            'name' => 'Laptop',
            'type' => 'produk',
            'slug' => 'laptop'
        ]);

        // Buat produk yang terkait dengan kategori
        $product = Product::create([
            'product_id' => 'PRD001',
            'categories_id' => $category->categories_id,
            'brand_id' => 1, // Asumsi brand dengan ID 1 ada
            'name' => 'Laptop Gaming',
            'description' => 'Laptop untuk gaming',
            'price' => 15000000,
            'weight' => 2500,
            'stock' => 10,
            'is_active' => true,
            'sold_count' => 0,
            'slug' => 'laptop-gaming'
        ]);

        // Act & Assert: Test relasi kategori ke produk
        $categoryProducts = $category->products;
        $this->assertCount(1, $categoryProducts, 'Kategori harus memiliki 1 produk');
        $this->assertEquals('Laptop Gaming', $categoryProducts->first()->name, 'Nama produk harus sesuai');

        // Test relasi produk ke kategori
        $productCategory = $product->category;
        $this->assertNotNull($productCategory, 'Produk harus memiliki kategori');
        $this->assertEquals('Laptop', $productCategory->name, 'Nama kategori harus sesuai');
    }

    /**
     * Test relasi kategori dengan service
     * 
     * Skenario:
     * 1. Buat kategori layanan
     * 2. Buat service yang terkait dengan kategori
     * 3. Verifikasi relasi kategori-service berfungsi
     */
    public function test_relasi_kategori_dengan_service()
    {
        // Arrange: Buat kategori layanan
        $category = Category::create([
            'name' => 'Perbaikan Hardware',
            'type' => 'layanan',
            'slug' => 'perbaikan-hardware'
        ]);

        // Buat service yang terkait dengan kategori
        $service = Service::create([
            'service_id' => 'SRV001',
            'categories_id' => $category->categories_id,
            'name' => 'Perbaikan Motherboard',
            'description' => 'Service perbaikan motherboard laptop',
            'price' => 500000,
            'thumbnail' => 'images/service/motherboard.jpg',
            'slug' => 'perbaikan-motherboard',
            'sold_count' => 0,
            'is_active' => true
        ]);

        // Act & Assert: Test relasi kategori ke service
        $categoryServices = $category->services;
        $this->assertCount(1, $categoryServices, 'Kategori harus memiliki 1 service');
        $this->assertEquals('Perbaikan Motherboard', $categoryServices->first()->name, 'Nama service harus sesuai');

        // Test relasi service ke kategori
        $serviceCategory = $service->category;
        $this->assertNotNull($serviceCategory, 'Service harus memiliki kategori');
        $this->assertEquals('Perbaikan Hardware', $serviceCategory->name, 'Nama kategori harus sesuai');
    }

    /**
     * Test filter kategori berdasarkan type
     * 
     * Skenario:
     * 1. Buat kategori dengan type 'produk' dan 'layanan'
     * 2. Filter kategori berdasarkan type
     * 3. Verifikasi hasil filter sesuai
     */
    public function test_filter_kategori_berdasarkan_type()
    {
        // Arrange: Buat kategori dengan type berbeda
        Category::create([
            'name' => 'Elektronik',
            'type' => 'produk',
            'slug' => 'elektronik'
        ]);

        Category::create([
            'name' => 'Komputer',
            'type' => 'produk',
            'slug' => 'komputer'
        ]);

        Category::create([
            'name' => 'Perbaikan',
            'type' => 'layanan',
            'slug' => 'perbaikan'
        ]);

        // Act: Filter kategori produk
        $productCategories = Category::where('type', 'produk')->get();
        $serviceCategories = Category::where('type', 'layanan')->get();

        // Assert: Verifikasi hasil filter
        $this->assertGreaterThanOrEqual(2, $productCategories->count(), 'Harus ada minimal 2 kategori produk');
        $this->assertGreaterThanOrEqual(1, $serviceCategories->count(), 'Harus ada minimal 1 kategori layanan');

        // Verifikasi semua kategori produk memiliki type 'produk'
        foreach ($productCategories as $category) {
            $this->assertEquals('produk', $category->type, 'Semua kategori harus bertipe produk');
        }

        // Verifikasi kategori layanan memiliki type 'layanan'
        $this->assertEquals('layanan', $serviceCategories->first()->type, 'Kategori harus bertipe layanan');
    }

    /**
     * Test validasi data kategori
     * 
     * Skenario:
     * 1. Coba buat kategori dengan data tidak valid
     * 2. Verifikasi error handling
     */
    public function test_validasi_data_kategori()
    {
        // Test: Nama kategori tidak boleh kosong
        $this->expectException(\Illuminate\Database\QueryException::class);

        Category::create([
            'name' => null, // Nama kosong
            'type' => 'produk',
            'slug' => 'test'
        ]);
    }

    /**
     * Test pencarian kategori berdasarkan nama
     * 
     * Skenario:
     * 1. Buat beberapa kategori
     * 2. Cari kategori berdasarkan nama
     * 3. Verifikasi hasil pencarian
     */
    public function test_pencarian_kategori_berdasarkan_nama()
    {
        // Arrange: Buat beberapa kategori
        Category::create([
            'name' => 'Laptop Gaming',
            'type' => 'produk',
            'slug' => 'laptop-gaming'
        ]);

        Category::create([
            'name' => 'Laptop Kantor',
            'type' => 'produk',
            'slug' => 'laptop-kantor'
        ]);

        Category::create([
            'name' => 'Smartphone',
            'type' => 'produk',
            'slug' => 'smartphone'
        ]);

        // Act: Cari kategori yang mengandung kata 'Laptop'
        $laptopCategories = Category::where('name', 'like', '%Laptop%')->get();

        // Assert: Verifikasi hasil pencarian
        $this->assertGreaterThanOrEqual(2, $laptopCategories->count(), 'Harus ditemukan minimal 2 kategori laptop');

        $categoryNames = $laptopCategories->pluck('name')->toArray();
        $this->assertContains('Laptop Gaming', $categoryNames, 'Harus mengandung Laptop Gaming');
        $this->assertContains('Laptop Kantor', $categoryNames, 'Harus mengandung Laptop Kantor');
    }
}
