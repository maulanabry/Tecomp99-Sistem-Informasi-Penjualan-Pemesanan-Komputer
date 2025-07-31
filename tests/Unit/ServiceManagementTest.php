<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Test Unit untuk Manajemen Data Servis
 * 
 * Test ini mencakup user story:
 * - Sebagai admin, saya ingin mengelola data servis sehingga dapat memperbarui dan mengubah informasi produk yang disediakan
 */
class ServiceManagementTest extends TestCase
{
    use DatabaseTransactions;

    private $serviceData;
    private $category;

    /**
     * Setup yang dijalankan sebelum setiap test
     * Menyiapkan data servis dan kategori untuk testing
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Buat kategori layanan untuk testing
        $this->category = Category::create([
            'name' => 'Perbaikan Hardware',
            'type' => 'layanan',
            'slug' => 'perbaikan-hardware'
        ]);

        // Data servis untuk testing
        $this->serviceData = [
            'service_id' => 'SRV001',
            'categories_id' => $this->category->categories_id,
            'name' => 'Perbaikan Motherboard',
            'description' => 'Layanan perbaikan motherboard laptop dan PC',
            'price' => 500000,
            'thumbnail' => 'images/services/motherboard-repair.jpg',
            'slug' => 'perbaikan-motherboard',
            'sold_count' => 0,
            'is_active' => true
        ];
    }

    /**
     * Test membuat servis baru
     * 
     * Skenario:
     * 1. Buat servis baru dengan data valid
     * 2. Verifikasi servis tersimpan di database
     * 3. Verifikasi relasi dengan kategori
     */
    public function test_admin_dapat_membuat_servis_baru()
    {
        // Act: Buat servis baru
        $service = Service::create($this->serviceData);

        // Assert: Verifikasi servis tersimpan
        $this->assertInstanceOf(Service::class, $service, 'Harus mengembalikan instance Service');
        $this->assertDatabaseHas('service', [
            'service_id' => 'SRV001',
            'name' => 'Perbaikan Motherboard',
            'price' => 500000,
            'is_active' => true
        ]);

        // Verifikasi relasi dengan kategori
        $this->assertEquals($this->category->categories_id, $service->categories_id, 'Kategori ID harus sesuai');
        $this->assertEquals('Perbaikan Hardware', $service->category->name, 'Nama kategori harus sesuai');
    }

    /**
     * Test membaca/menampilkan data servis
     * 
     * Skenario:
     * 1. Buat beberapa servis di database
     * 2. Ambil semua servis
     * 3. Verifikasi data servis yang diambil
     */
    public function test_admin_dapat_membaca_data_servis()
    {
        // Arrange: Buat beberapa servis
        $service1 = Service::create($this->serviceData);

        $service2 = Service::create([
            'service_id' => 'SRV002',
            'categories_id' => $this->category->categories_id,
            'name' => 'Perbaikan LCD',
            'description' => 'Layanan perbaikan LCD laptop',
            'price' => 750000,
            'thumbnail' => 'images/services/lcd-repair.jpg',
            'slug' => 'perbaikan-lcd',
            'sold_count' => 3,
            'is_active' => true
        ]);

        // Act: Ambil semua servis
        $services = Service::all();

        // Assert: Verifikasi data servis
        $this->assertGreaterThan(0, $services->count(), 'Harus ada servis');

        // Verifikasi servis pertama
        $foundService1 = $services->where('service_id', 'SRV001')->first();
        $this->assertNotNull($foundService1, 'Servis SRV001 harus ditemukan');
        $this->assertEquals('Perbaikan Motherboard', $foundService1->name, 'Nama servis harus sesuai');
        $this->assertEquals(500000, $foundService1->price, 'Harga servis harus sesuai');

        // Verifikasi servis kedua
        $foundService2 = $services->where('service_id', 'SRV002')->first();
        $this->assertNotNull($foundService2, 'Servis SRV002 harus ditemukan');
        $this->assertEquals('Perbaikan LCD', $foundService2->name, 'Nama servis harus sesuai');
        $this->assertEquals(3, $foundService2->sold_count, 'Sold count harus sesuai');
    }

    /**
     * Test mengupdate data servis
     * 
     * Skenario:
     * 1. Buat servis baru
     * 2. Update data servis
     * 3. Verifikasi perubahan tersimpan di database
     */
    public function test_admin_dapat_mengupdate_servis()
    {
        // Arrange: Buat servis
        $service = Service::create($this->serviceData);

        // Act: Update servis
        $updatedData = [
            'name' => 'Perbaikan Motherboard Premium',
            'price' => 600000,
            'description' => 'Layanan perbaikan motherboard laptop dan PC dengan garansi'
        ];
        $service->update($updatedData);

        // Assert: Verifikasi perubahan tersimpan
        $service->refresh();
        $this->assertEquals('Perbaikan Motherboard Premium', $service->name, 'Nama servis harus terupdate');
        $this->assertEquals(600000, $service->price, 'Harga servis harus terupdate');

        // Verifikasi di database
        $this->assertDatabaseHas('service', [
            'service_id' => 'SRV001',
            'name' => 'Perbaikan Motherboard Premium',
            'price' => 600000
        ]);
    }

    /**
     * Test soft delete servis
     * 
     * Skenario:
     * 1. Buat servis baru
     * 2. Hapus servis (soft delete)
     * 3. Verifikasi servis tidak muncul di query normal
     * 4. Verifikasi servis masih ada dengan withTrashed()
     */
    public function test_admin_dapat_menghapus_servis_soft_delete()
    {
        // Arrange: Buat servis
        $service = Service::create($this->serviceData);
        $serviceId = $service->service_id;

        // Act: Hapus servis (soft delete)
        $service->delete();

        // Assert: Verifikasi servis tidak muncul di query normal
        $this->assertNull(Service::find($serviceId), 'Servis tidak boleh ditemukan setelah dihapus');

        // Verifikasi servis masih ada dengan withTrashed()
        $trashedService = Service::withTrashed()->find($serviceId);
        $this->assertNotNull($trashedService, 'Servis harus masih ada dengan withTrashed()');
        $this->assertNotNull($trashedService->deleted_at, 'deleted_at harus ter-set');
    }

    /**
     * Test restore servis yang sudah dihapus
     * 
     * Skenario:
     * 1. Buat dan hapus servis
     * 2. Restore servis
     * 3. Verifikasi servis kembali muncul di query normal
     */
    public function test_admin_dapat_restore_servis_yang_dihapus()
    {
        // Arrange: Buat dan hapus servis
        $service = Service::create($this->serviceData);
        $serviceId = $service->service_id;
        $service->delete();

        // Verifikasi servis terhapus
        $this->assertNull(Service::find($serviceId), 'Servis harus terhapus');

        // Act: Restore servis
        $trashedService = Service::withTrashed()->find($serviceId);
        $trashedService->restore();

        // Assert: Verifikasi servis kembali muncul
        $restoredService = Service::find($serviceId);
        $this->assertNotNull($restoredService, 'Servis harus kembali muncul setelah restore');
        $this->assertNull($restoredService->deleted_at, 'deleted_at harus null setelah restore');
        $this->assertEquals('Perbaikan Motherboard', $restoredService->name, 'Data servis harus sama setelah restore');
    }

    /**
     * Test update sold count servis
     * 
     * Skenario:
     * 1. Buat servis dengan sold_count 0
     * 2. Update sold_count (simulasi penjualan)
     * 3. Verifikasi sold_count terupdate
     */
    public function test_admin_dapat_mengelola_sold_count_servis()
    {
        // Arrange: Buat servis dengan sold_count 0
        $service = Service::create($this->serviceData);
        $this->assertEquals(0, $service->sold_count, 'Sold count awal harus 0');

        // Act: Update sold_count
        $service->update(['sold_count' => $service->sold_count + 2]);

        // Assert: Verifikasi sold_count terupdate
        $service->refresh();
        $this->assertEquals(2, $service->sold_count, 'Sold count harus menjadi 2');

        // Verifikasi di database
        $this->assertDatabaseHas('service', [
            'service_id' => 'SRV001',
            'sold_count' => 2
        ]);
    }

    /**
     * Test mengaktifkan/menonaktifkan servis
     * 
     * Skenario:
     * 1. Buat servis aktif
     * 2. Nonaktifkan servis
     * 3. Aktifkan kembali servis
     * 4. Verifikasi status is_active
     */
    public function test_admin_dapat_mengaktifkan_menonaktifkan_servis()
    {
        // Arrange: Buat servis aktif
        $service = Service::create($this->serviceData);
        $this->assertTrue($service->is_active, 'Servis harus aktif saat dibuat');

        // Act: Nonaktifkan servis
        $service->update(['is_active' => false]);

        // Assert: Verifikasi servis nonaktif
        $service->refresh();
        $this->assertFalse($service->is_active, 'Servis harus nonaktif');

        // Act: Aktifkan kembali servis
        $service->update(['is_active' => true]);

        // Assert: Verifikasi servis aktif kembali
        $service->refresh();
        $this->assertTrue($service->is_active, 'Servis harus aktif kembali');
    }

    /**
     * Test thumbnail URL accessor
     * 
     * Skenario:
     * 1. Buat servis dengan thumbnail
     * 2. Test accessor thumbnail_url
     * 3. Verifikasi URL thumbnail ter-generate dengan benar
     */
    public function test_thumbnail_url_accessor()
    {
        // Arrange: Buat servis dengan thumbnail
        $service = Service::create($this->serviceData);

        // Act: Ambil thumbnail URL
        $thumbnailUrl = $service->thumbnail_url;

        // Assert: Verifikasi thumbnail URL
        $this->assertNotNull($thumbnailUrl, 'Thumbnail URL tidak boleh null');
        $this->assertStringContainsString('motherboard-repair.jpg', $thumbnailUrl, 'Thumbnail URL harus mengandung nama file');
        $this->assertStringStartsWith(url('/'), $thumbnailUrl, 'Thumbnail URL harus dimulai dengan base URL');
    }

    /**
     * Test servis tanpa thumbnail
     * 
     * Skenario:
     * 1. Buat servis dengan thumbnail kosong
     * 2. Verifikasi thumbnail_url mengembalikan null
     */
    public function test_servis_tanpa_thumbnail()
    {
        // Arrange: Buat servis dengan thumbnail kosong (empty string instead of null)
        $serviceData = $this->serviceData;
        $serviceData['thumbnail'] = '';
        $service = Service::create($serviceData);

        // Act & Assert: Verifikasi thumbnail_url null untuk thumbnail kosong
        $this->assertNull($service->thumbnail_url, 'Thumbnail URL harus null jika thumbnail kosong');
    }

    /**
     * Test filter servis berdasarkan kategori
     * 
     * Skenario:
     * 1. Buat servis dengan kategori berbeda
     * 2. Filter servis berdasarkan kategori
     * 3. Verifikasi hasil filter
     */
    public function test_filter_servis_berdasarkan_kategori()
    {
        // Arrange: Buat kategori kedua
        $category2 = Category::create([
            'name' => 'Perbaikan Software',
            'type' => 'layanan',
            'slug' => 'perbaikan-software'
        ]);

        // Buat servis dengan kategori berbeda
        Service::create($this->serviceData); // Kategori Hardware

        Service::create([
            'service_id' => 'SRV002',
            'categories_id' => $category2->categories_id,
            'name' => 'Install Windows',
            'description' => 'Layanan instalasi Windows',
            'price' => 200000,
            'thumbnail' => 'images/services/windows-install.jpg',
            'slug' => 'install-windows',
            'sold_count' => 0,
            'is_active' => true
        ]);

        // Act: Filter servis berdasarkan kategori
        $hardwareServices = Service::where('categories_id', $this->category->categories_id)->get();
        $softwareServices = Service::where('categories_id', $category2->categories_id)->get();

        // Assert: Verifikasi hasil filter
        $this->assertGreaterThanOrEqual(1, $hardwareServices->count(), 'Harus ada minimal 1 servis hardware');
        $this->assertGreaterThanOrEqual(1, $softwareServices->count(), 'Harus ada minimal 1 servis software');

        $this->assertEquals('Perbaikan Motherboard', $hardwareServices->first()->name, 'Nama servis hardware harus sesuai');
        $this->assertEquals('Install Windows', $softwareServices->first()->name, 'Nama servis software harus sesuai');
    }

    /**
     * Test pencarian servis berdasarkan nama
     * 
     * Skenario:
     * 1. Buat beberapa servis
     * 2. Cari servis berdasarkan nama
     * 3. Verifikasi hasil pencarian
     */
    public function test_pencarian_servis_berdasarkan_nama()
    {
        // Arrange: Buat beberapa servis
        Service::create($this->serviceData);

        Service::create([
            'service_id' => 'SRV002',
            'categories_id' => $this->category->categories_id,
            'name' => 'Perbaikan RAM',
            'description' => 'Layanan perbaikan RAM laptop',
            'price' => 300000,
            'thumbnail' => 'images/services/ram-repair.jpg',
            'slug' => 'perbaikan-ram',
            'sold_count' => 0,
            'is_active' => true
        ]);

        Service::create([
            'service_id' => 'SRV003',
            'categories_id' => $this->category->categories_id,
            'name' => 'Upgrade SSD',
            'description' => 'Layanan upgrade SSD laptop',
            'price' => 400000,
            'thumbnail' => 'images/services/ssd-upgrade.jpg',
            'slug' => 'upgrade-ssd',
            'sold_count' => 0,
            'is_active' => true
        ]);

        // Act: Cari servis yang mengandung kata 'Perbaikan'
        $repairServices = Service::where('name', 'like', '%Perbaikan%')->get();

        // Assert: Verifikasi hasil pencarian
        $this->assertGreaterThanOrEqual(2, $repairServices->count(), 'Harus ditemukan minimal 2 servis perbaikan');

        $serviceNames = $repairServices->pluck('name')->toArray();
        $this->assertContains('Perbaikan Motherboard', $serviceNames, 'Harus mengandung Perbaikan Motherboard');
        $this->assertContains('Perbaikan RAM', $serviceNames, 'Harus mengandung Perbaikan RAM');
    }

    /**
     * Test filter servis aktif/nonaktif
     * 
     * Skenario:
     * 1. Buat servis aktif dan nonaktif
     * 2. Filter servis berdasarkan status aktif
     * 3. Verifikasi hasil filter
     */
    public function test_filter_servis_berdasarkan_status_aktif()
    {
        // Arrange: Buat servis aktif
        Service::create($this->serviceData);

        // Buat servis nonaktif
        Service::create([
            'service_id' => 'SRV002',
            'categories_id' => $this->category->categories_id,
            'name' => 'Perbaikan LCD',
            'description' => 'Layanan perbaikan LCD laptop',
            'price' => 750000,
            'thumbnail' => 'images/services/lcd-repair.jpg',
            'slug' => 'perbaikan-lcd',
            'sold_count' => 0,
            'is_active' => false // Nonaktif
        ]);

        // Act: Filter servis berdasarkan status
        $activeServices = Service::where('is_active', true)->get();
        $inactiveServices = Service::where('is_active', false)->get();

        // Assert: Verifikasi hasil filter
        $this->assertGreaterThanOrEqual(1, $activeServices->count(), 'Harus ada minimal 1 servis aktif');
        $this->assertGreaterThanOrEqual(1, $inactiveServices->count(), 'Harus ada minimal 1 servis nonaktif');

        $this->assertEquals('Perbaikan Motherboard', $activeServices->first()->name, 'Servis aktif harus sesuai');
        $this->assertEquals('Perbaikan LCD', $inactiveServices->first()->name, 'Servis nonaktif harus sesuai');
    }

    /**
     * Test filter servis berdasarkan range harga
     * 
     * Skenario:
     * 1. Buat servis dengan harga berbeda
     * 2. Filter servis berdasarkan range harga
     * 3. Verifikasi hasil filter
     */
    public function test_filter_servis_berdasarkan_range_harga()
    {
        // Arrange: Buat servis dengan harga berbeda
        Service::create($this->serviceData); // Harga 500000

        Service::create([
            'service_id' => 'SRV002',
            'categories_id' => $this->category->categories_id,
            'name' => 'Install Software',
            'description' => 'Layanan instalasi software',
            'price' => 100000, // Harga murah
            'thumbnail' => 'images/services/software-install.jpg',
            'slug' => 'install-software',
            'sold_count' => 0,
            'is_active' => true
        ]);

        Service::create([
            'service_id' => 'SRV003',
            'categories_id' => $this->category->categories_id,
            'name' => 'Perbaikan Laptop Premium',
            'description' => 'Layanan perbaikan laptop premium',
            'price' => 1000000, // Harga mahal
            'thumbnail' => 'images/services/premium-repair.jpg',
            'slug' => 'perbaikan-laptop-premium',
            'sold_count' => 0,
            'is_active' => true
        ]);

        // Act: Filter servis berdasarkan range harga
        $cheapServices = Service::where('price', '<=', 200000)->get();
        $midRangeServices = Service::whereBetween('price', [200001, 800000])->get();
        $expensiveServices = Service::where('price', '>', 800000)->get();

        // Assert: Verifikasi hasil filter
        $this->assertGreaterThanOrEqual(1, $cheapServices->count(), 'Harus ada minimal 1 servis murah');
        $this->assertGreaterThanOrEqual(1, $midRangeServices->count(), 'Harus ada minimal 1 servis menengah');
        $this->assertGreaterThanOrEqual(1, $expensiveServices->count(), 'Harus ada minimal 1 servis mahal');

        $this->assertEquals('Install Software', $cheapServices->first()->name, 'Servis murah harus sesuai');
        $this->assertEquals('Perbaikan Motherboard', $midRangeServices->first()->name, 'Servis menengah harus sesuai');
        $this->assertEquals('Perbaikan Laptop Premium', $expensiveServices->first()->name, 'Servis mahal harus sesuai');
    }

    /**
     * Test sorting servis berdasarkan popularitas (sold_count)
     * 
     * Skenario:
     * 1. Buat servis dengan sold_count berbeda
     * 2. Sort servis berdasarkan sold_count
     * 3. Verifikasi urutan sorting
     */
    public function test_sorting_servis_berdasarkan_popularitas()
    {
        // Arrange: Buat servis dengan sold_count berbeda
        $service1 = Service::create(array_merge($this->serviceData, ['sold_count' => 5]));

        $service2 = Service::create([
            'service_id' => 'SRV002',
            'categories_id' => $this->category->categories_id,
            'name' => 'Perbaikan LCD',
            'description' => 'Layanan perbaikan LCD laptop',
            'price' => 750000,
            'thumbnail' => 'images/services/lcd-repair.jpg',
            'slug' => 'perbaikan-lcd',
            'sold_count' => 10, // Paling populer
            'is_active' => true
        ]);

        $service3 = Service::create([
            'service_id' => 'SRV003',
            'categories_id' => $this->category->categories_id,
            'name' => 'Perbaikan RAM',
            'description' => 'Layanan perbaikan RAM laptop',
            'price' => 300000,
            'thumbnail' => 'images/services/ram-repair.jpg',
            'slug' => 'perbaikan-ram',
            'sold_count' => 2, // Paling sedikit
            'is_active' => true
        ]);

        // Act: Sort servis yang dibuat dalam test berdasarkan popularitas (descending)
        $createdServiceIds = [$service1->service_id, $service2->service_id, $service3->service_id];
        $popularServices = Service::whereIn('service_id', $createdServiceIds)
            ->orderBy('sold_count', 'desc')
            ->get();

        // Assert: Verifikasi urutan sorting dari servis yang dibuat dalam test
        $this->assertGreaterThanOrEqual(3, $popularServices->count(), 'Harus ada minimal 3 servis yang dibuat dalam test');

        // Verifikasi servis dengan sold_count tertinggi ada di urutan pertama
        $highestSoldCount = $popularServices->first()->sold_count;
        $this->assertGreaterThanOrEqual(10, $highestSoldCount, 'Sold count tertinggi harus minimal 10');

        // Verifikasi servis dengan sold_count terendah ada di urutan terakhir
        $lowestSoldCount = $popularServices->last()->sold_count;
        $this->assertLessThanOrEqual(2, $lowestSoldCount, 'Sold count terendah harus maksimal 2');

        // Verifikasi urutan descending
        for ($i = 0; $i < $popularServices->count() - 1; $i++) {
            $this->assertGreaterThanOrEqual(
                $popularServices[$i + 1]->sold_count,
                $popularServices[$i]->sold_count,
                'Urutan sold_count harus descending'
            );
        }
    }
}
