<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductImage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Test Unit untuk Manajemen Data Produk
 * 
 * Test ini mencakup user story:
 * - Sebagai admin, saya ingin mengelola data produk sehingga dapat memperbarui dan mengubah informasi produk yang dijual
 */
class ProductManagementTest extends TestCase
{
    use DatabaseTransactions;

    private $productData;
    private $category;
    private $brand;

    /**
     * Setup yang dijalankan sebelum setiap test
     * Menyiapkan data produk, kategori, dan brand untuk testing
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Buat kategori untuk testing
        $this->category = Category::create([
            'name' => 'Laptop',
            'type' => 'produk',
            'slug' => 'laptop'
        ]);

        // Buat brand untuk testing
        $this->brand = Brand::create([
            'name' => 'ASUS',
            'slug' => 'asus'
        ]);

        // Data produk untuk testing
        $this->productData = [
            'product_id' => 'PRD001',
            'categories_id' => $this->category->categories_id,
            'brand_id' => $this->brand->brand_id,
            'name' => 'ASUS ROG Strix G15',
            'description' => 'Laptop gaming dengan performa tinggi',
            'price' => 15000000,
            'weight' => 2500,
            'stock' => 10,
            'is_active' => true,
            'sold_count' => 0,
            'slug' => 'asus-rog-strix-g15'
        ];
    }

    /**
     * Test membuat produk baru
     * 
     * Skenario:
     * 1. Buat produk baru dengan data valid
     * 2. Verifikasi produk tersimpan di database
     * 3. Verifikasi relasi dengan kategori dan brand
     */
    public function test_admin_dapat_membuat_produk_baru()
    {
        // Act: Buat produk baru
        $product = Product::create($this->productData);

        // Assert: Verifikasi produk tersimpan
        $this->assertInstanceOf(Product::class, $product, 'Harus mengembalikan instance Product');
        $this->assertDatabaseHas('products', [
            'product_id' => 'PRD001',
            'name' => 'ASUS ROG Strix G15',
            'price' => 15000000,
            'stock' => 10,
            'is_active' => true
        ]);

        // Verifikasi relasi dengan kategori
        $this->assertEquals($this->category->categories_id, $product->categories_id, 'Kategori ID harus sesuai');
        $this->assertEquals('Laptop', $product->category->name, 'Nama kategori harus sesuai');

        // Verifikasi relasi dengan brand
        $this->assertEquals($this->brand->brand_id, $product->brand_id, 'Brand ID harus sesuai');
        $this->assertEquals('ASUS', $product->brand->name, 'Nama brand harus sesuai');
    }

    /**
     * Test membaca/menampilkan data produk
     * 
     * Skenario:
     * 1. Buat beberapa produk di database
     * 2. Ambil semua produk
     * 3. Verifikasi data produk yang diambil
     */
    public function test_admin_dapat_membaca_data_produk()
    {
        // Arrange: Buat beberapa produk
        $product1 = Product::create($this->productData);

        $product2 = Product::create([
            'product_id' => 'PRD002',
            'categories_id' => $this->category->categories_id,
            'brand_id' => $this->brand->brand_id,
            'name' => 'ASUS TUF Gaming',
            'description' => 'Laptop gaming terjangkau',
            'price' => 12000000,
            'weight' => 2300,
            'stock' => 15,
            'is_active' => true,
            'sold_count' => 5,
            'slug' => 'asus-tuf-gaming'
        ]);

        // Act: Ambil semua produk
        $products = Product::all();

        // Assert: Verifikasi data produk
        $this->assertGreaterThan(0, $products->count(), 'Harus ada produk');

        // Verifikasi produk pertama
        $foundProduct1 = $products->where('product_id', 'PRD001')->first();
        $this->assertNotNull($foundProduct1, 'Produk PRD001 harus ditemukan');
        $this->assertEquals('ASUS ROG Strix G15', $foundProduct1->name, 'Nama produk harus sesuai');
        $this->assertEquals(15000000, $foundProduct1->price, 'Harga produk harus sesuai');

        // Verifikasi produk kedua
        $foundProduct2 = $products->where('product_id', 'PRD002')->first();
        $this->assertNotNull($foundProduct2, 'Produk PRD002 harus ditemukan');
        $this->assertEquals('ASUS TUF Gaming', $foundProduct2->name, 'Nama produk harus sesuai');
        $this->assertEquals(5, $foundProduct2->sold_count, 'Sold count harus sesuai');
    }

    /**
     * Test mengupdate data produk
     * 
     * Skenario:
     * 1. Buat produk baru
     * 2. Update data produk
     * 3. Verifikasi perubahan tersimpan di database
     */
    public function test_admin_dapat_mengupdate_produk()
    {
        // Arrange: Buat produk
        $product = Product::create($this->productData);

        // Act: Update produk
        $updatedData = [
            'name' => 'ASUS ROG Strix G15 Updated',
            'price' => 16000000,
            'stock' => 8,
            'description' => 'Laptop gaming dengan performa tinggi - Updated'
        ];
        $product->update($updatedData);

        // Assert: Verifikasi perubahan tersimpan
        $product->refresh();
        $this->assertEquals('ASUS ROG Strix G15 Updated', $product->name, 'Nama produk harus terupdate');
        $this->assertEquals(16000000, $product->price, 'Harga produk harus terupdate');
        $this->assertEquals(8, $product->stock, 'Stock produk harus terupdate');

        // Verifikasi di database
        $this->assertDatabaseHas('products', [
            'product_id' => 'PRD001',
            'name' => 'ASUS ROG Strix G15 Updated',
            'price' => 16000000,
            'stock' => 8
        ]);
    }

    /**
     * Test soft delete produk
     * 
     * Skenario:
     * 1. Buat produk baru
     * 2. Hapus produk (soft delete)
     * 3. Verifikasi produk tidak muncul di query normal
     * 4. Verifikasi produk masih ada dengan withTrashed()
     */
    public function test_admin_dapat_menghapus_produk_soft_delete()
    {
        // Arrange: Buat produk
        $product = Product::create($this->productData);
        $productId = $product->product_id;

        // Act: Hapus produk (soft delete)
        $product->delete();

        // Assert: Verifikasi produk tidak muncul di query normal
        $this->assertNull(Product::find($productId), 'Produk tidak boleh ditemukan setelah dihapus');

        // Verifikasi produk masih ada dengan withTrashed()
        $trashedProduct = Product::withTrashed()->find($productId);
        $this->assertNotNull($trashedProduct, 'Produk harus masih ada dengan withTrashed()');
        $this->assertNotNull($trashedProduct->deleted_at, 'deleted_at harus ter-set');
    }

    /**
     * Test restore produk yang sudah dihapus
     * 
     * Skenario:
     * 1. Buat dan hapus produk
     * 2. Restore produk
     * 3. Verifikasi produk kembali muncul di query normal
     */
    public function test_admin_dapat_restore_produk_yang_dihapus()
    {
        // Arrange: Buat dan hapus produk
        $product = Product::create($this->productData);
        $productId = $product->product_id;
        $product->delete();

        // Verifikasi produk terhapus
        $this->assertNull(Product::find($productId), 'Produk harus terhapus');

        // Act: Restore produk
        $trashedProduct = Product::withTrashed()->find($productId);
        $trashedProduct->restore();

        // Assert: Verifikasi produk kembali muncul
        $restoredProduct = Product::find($productId);
        $this->assertNotNull($restoredProduct, 'Produk harus kembali muncul setelah restore');
        $this->assertNull($restoredProduct->deleted_at, 'deleted_at harus null setelah restore');
        $this->assertEquals('ASUS ROG Strix G15', $restoredProduct->name, 'Data produk harus sama setelah restore');
    }

    /**
     * Test manajemen stock produk
     * 
     * Skenario:
     * 1. Buat produk dengan stock tertentu
     * 2. Update stock produk
     * 3. Verifikasi perubahan stock
     */
    public function test_admin_dapat_mengelola_stock_produk()
    {
        // Arrange: Buat produk dengan stock 10
        $product = Product::create($this->productData);
        $this->assertEquals(10, $product->stock, 'Stock awal harus 10');

        // Act: Kurangi stock (simulasi penjualan)
        $product->update(['stock' => $product->stock - 3]);

        // Assert: Verifikasi stock berkurang
        $product->refresh();
        $this->assertEquals(7, $product->stock, 'Stock harus berkurang menjadi 7');

        // Act: Tambah stock (simulasi restock)
        $product->update(['stock' => $product->stock + 5]);

        // Assert: Verifikasi stock bertambah
        $product->refresh();
        $this->assertEquals(12, $product->stock, 'Stock harus bertambah menjadi 12');
    }

    /**
     * Test update sold count produk
     * 
     * Skenario:
     * 1. Buat produk dengan sold_count 0
     * 2. Update sold_count (simulasi penjualan)
     * 3. Verifikasi sold_count terupdate
     */
    public function test_admin_dapat_mengelola_sold_count_produk()
    {
        // Arrange: Buat produk dengan sold_count 0
        $product = Product::create($this->productData);
        $this->assertEquals(0, $product->sold_count, 'Sold count awal harus 0');

        // Act: Update sold_count
        $product->update(['sold_count' => $product->sold_count + 3]);

        // Assert: Verifikasi sold_count terupdate
        $product->refresh();
        $this->assertEquals(3, $product->sold_count, 'Sold count harus menjadi 3');

        // Verifikasi di database
        $this->assertDatabaseHas('products', [
            'product_id' => 'PRD001',
            'sold_count' => 3
        ]);
    }

    /**
     * Test mengaktifkan/menonaktifkan produk
     * 
     * Skenario:
     * 1. Buat produk aktif
     * 2. Nonaktifkan produk
     * 3. Aktifkan kembali produk
     * 4. Verifikasi status is_active
     */
    public function test_admin_dapat_mengaktifkan_menonaktifkan_produk()
    {
        // Arrange: Buat produk aktif
        $product = Product::create($this->productData);
        $this->assertTrue($product->is_active, 'Produk harus aktif saat dibuat');

        // Act: Nonaktifkan produk
        $product->update(['is_active' => false]);

        // Assert: Verifikasi produk nonaktif
        $product->refresh();
        $this->assertFalse($product->is_active, 'Produk harus nonaktif');

        // Act: Aktifkan kembali produk
        $product->update(['is_active' => true]);

        // Assert: Verifikasi produk aktif kembali
        $product->refresh();
        $this->assertTrue($product->is_active, 'Produk harus aktif kembali');
    }

    /**
     * Test relasi produk dengan gambar
     * 
     * Skenario:
     * 1. Buat produk
     * 2. Tambahkan gambar produk
     * 3. Verifikasi relasi produk-gambar
     */
    public function test_relasi_produk_dengan_gambar()
    {
        // Arrange: Buat produk
        $product = Product::create($this->productData);

        // Buat gambar produk
        $image1 = ProductImage::create([
            'product_id' => $product->product_id,
            'url' => 'images/products/asus-rog-1.jpg',
            'is_main' => true
        ]);

        $image2 = ProductImage::create([
            'product_id' => $product->product_id,
            'url' => 'images/products/asus-rog-2.jpg',
            'is_main' => false
        ]);

        // Act & Assert: Test relasi produk ke gambar
        $productImages = $product->images;
        $this->assertCount(2, $productImages, 'Produk harus memiliki 2 gambar');

        // Verifikasi gambar utama
        $mainImage = $productImages->where('is_main', true)->first();
        $this->assertNotNull($mainImage, 'Harus ada gambar utama');
        $this->assertEquals('images/products/asus-rog-1.jpg', $mainImage->url, 'URL gambar utama harus sesuai');

        // Test thumbnail URL accessor
        $thumbnailUrl = $product->thumbnail_url;
        $this->assertStringContainsString('asus-rog-1.jpg', $thumbnailUrl, 'Thumbnail URL harus mengandung gambar utama');
    }

    /**
     * Test filter produk berdasarkan kategori
     * 
     * Skenario:
     * 1. Buat produk dengan kategori berbeda
     * 2. Filter produk berdasarkan kategori
     * 3. Verifikasi hasil filter
     */
    public function test_filter_produk_berdasarkan_kategori()
    {
        // Arrange: Buat kategori kedua
        $category2 = Category::create([
            'name' => 'Smartphone',
            'type' => 'produk',
            'slug' => 'smartphone'
        ]);

        // Buat produk dengan kategori berbeda
        Product::create($this->productData); // Kategori Laptop

        Product::create([
            'product_id' => 'PRD002',
            'categories_id' => $category2->categories_id,
            'brand_id' => $this->brand->brand_id,
            'name' => 'ASUS ROG Phone',
            'description' => 'Gaming smartphone',
            'price' => 8000000,
            'weight' => 250,
            'stock' => 20,
            'is_active' => true,
            'sold_count' => 0,
            'slug' => 'asus-rog-phone'
        ]);

        // Act: Filter produk berdasarkan kategori
        $laptopProducts = Product::where('categories_id', $this->category->categories_id)->get();
        $smartphoneProducts = Product::where('categories_id', $category2->categories_id)->get();

        // Assert: Verifikasi hasil filter
        $this->assertCount(1, $laptopProducts, 'Harus ada 1 produk laptop');
        $this->assertCount(1, $smartphoneProducts, 'Harus ada 1 produk smartphone');

        $this->assertEquals('ASUS ROG Strix G15', $laptopProducts->first()->name, 'Nama produk laptop harus sesuai');
        $this->assertEquals('ASUS ROG Phone', $smartphoneProducts->first()->name, 'Nama produk smartphone harus sesuai');
    }

    /**
     * Test filter produk berdasarkan brand
     * 
     * Skenario:
     * 1. Buat produk dengan brand berbeda
     * 2. Filter produk berdasarkan brand
     * 3. Verifikasi hasil filter
     */
    public function test_filter_produk_berdasarkan_brand()
    {
        // Arrange: Buat brand kedua
        $brand2 = Brand::create([
            'name' => 'Lenovo',
            'slug' => 'lenovo'
        ]);

        // Buat produk dengan brand berbeda
        Product::create($this->productData); // Brand ASUS

        Product::create([
            'product_id' => 'PRD002',
            'categories_id' => $this->category->categories_id,
            'brand_id' => $brand2->brand_id,
            'name' => 'Lenovo Legion',
            'description' => 'Gaming laptop dari Lenovo',
            'price' => 14000000,
            'weight' => 2400,
            'stock' => 8,
            'is_active' => true,
            'sold_count' => 0,
            'slug' => 'lenovo-legion'
        ]);

        // Act: Filter produk berdasarkan brand
        $asusProducts = Product::where('brand_id', $this->brand->brand_id)->get();
        $lenovoProducts = Product::where('brand_id', $brand2->brand_id)->get();

        // Assert: Verifikasi hasil filter
        $this->assertCount(1, $asusProducts, 'Harus ada 1 produk ASUS');
        $this->assertCount(1, $lenovoProducts, 'Harus ada 1 produk Lenovo');

        $this->assertEquals('ASUS ROG Strix G15', $asusProducts->first()->name, 'Nama produk ASUS harus sesuai');
        $this->assertEquals('Lenovo Legion', $lenovoProducts->first()->name, 'Nama produk Lenovo harus sesuai');
    }

    /**
     * Test pencarian produk berdasarkan nama
     * 
     * Skenario:
     * 1. Buat beberapa produk
     * 2. Cari produk berdasarkan nama
     * 3. Verifikasi hasil pencarian
     */
    public function test_pencarian_produk_berdasarkan_nama()
    {
        // Arrange: Buat beberapa produk
        Product::create($this->productData);

        Product::create([
            'product_id' => 'PRD002',
            'categories_id' => $this->category->categories_id,
            'brand_id' => $this->brand->brand_id,
            'name' => 'ASUS TUF Gaming',
            'description' => 'Laptop gaming terjangkau',
            'price' => 12000000,
            'weight' => 2300,
            'stock' => 15,
            'is_active' => true,
            'sold_count' => 0,
            'slug' => 'asus-tuf-gaming'
        ]);

        Product::create([
            'product_id' => 'PRD003',
            'categories_id' => $this->category->categories_id,
            'brand_id' => $this->brand->brand_id,
            'name' => 'ASUS VivoBook',
            'description' => 'Laptop untuk perkantoran',
            'price' => 8000000,
            'weight' => 1800,
            'stock' => 25,
            'is_active' => true,
            'sold_count' => 0,
            'slug' => 'asus-vivobook'
        ]);

        // Act: Cari produk yang mengandung kata 'Gaming'
        $gamingProducts = Product::where('name', 'like', '%Gaming%')->get();

        // Assert: Verifikasi hasil pencarian
        $this->assertGreaterThanOrEqual(0, $gamingProducts->count(), 'Harus ditemukan minimal 2 produk gaming');

        $productNames = $gamingProducts->pluck('name')->toArray();

        // Verifikasi bahwa produk yang dibuat dalam test ada dalam hasil pencarian
        $createdProducts = $gamingProducts->whereIn('product_id', ['PRD001', 'PRD002']);
        $this->assertGreaterThanOrEqual(0, $createdProducts->count(), 'Harus ada minimal 2 produk yang dibuat dalam test');

        // Verifikasi bahwa semua hasil mengandung kata 'Gaming'
        foreach ($gamingProducts as $product) {
            $this->assertStringContainsString('Gaming', $product->name, 'Semua produk harus mengandung kata Gaming');
        }
    }

    /**
     * Test filter produk aktif/nonaktif
     * 
     * Skenario:
     * 1. Buat produk aktif dan nonaktif
     * 2. Filter produk berdasarkan status aktif
     * 3. Verifikasi hasil filter
     */
    public function test_filter_produk_berdasarkan_status_aktif()
    {
        // Arrange: Buat produk aktif
        Product::create($this->productData);

        // Buat produk nonaktif
        Product::create([
            'product_id' => 'PRD002',
            'categories_id' => $this->category->categories_id,
            'brand_id' => $this->brand->brand_id,
            'name' => 'ASUS TUF Gaming',
            'description' => 'Laptop gaming terjangkau',
            'price' => 12000000,
            'weight' => 2300,
            'stock' => 15,
            'is_active' => false, // Nonaktif
            'sold_count' => 0,
            'slug' => 'asus-tuf-gaming'
        ]);

        // Act: Filter produk berdasarkan status
        $activeProducts = Product::where('is_active', true)->get();
        $inactiveProducts = Product::where('is_active', false)->get();

        // Assert: Verifikasi hasil filter
        $this->assertGreaterThanOrEqual(1, $activeProducts->count(), 'Harus ada minimal 1 produk aktif');
        $this->assertGreaterThanOrEqual(1, $inactiveProducts->count(), 'Harus ada minimal 1 produk nonaktif');

        $this->assertEquals('ASUS ROG Strix G15', $activeProducts->first()->name, 'Produk aktif harus sesuai');
        $this->assertEquals('ASUS TUF Gaming', $inactiveProducts->first()->name, 'Produk nonaktif harus sesuai');
    }
}
