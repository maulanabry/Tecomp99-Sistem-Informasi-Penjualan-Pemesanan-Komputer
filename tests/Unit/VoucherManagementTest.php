<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;

/**
 * Test Unit untuk Manajemen Data Voucher
 * 
 * Test ini mencakup user story:
 * - Sebagai admin, saya ingin mengelola data voucher sehingga dapat memberikan penawaran khusus kepada pelanggan
 */
class VoucherManagementTest extends TestCase
{
    use DatabaseTransactions;

    private $voucherData;

    /**
     * Setup yang dijalankan sebelum setiap test
     * Menyiapkan data voucher untuk testing
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Data voucher untuk testing
        $this->voucherData = [
            'code' => 'DISKON50',
            'name' => 'Diskon 50%',
            'type' => 'percentage',
            'discount_percentage' => 50.00,
            'discount_amount' => null,
            'minimum_order_amount' => 100000,
            'is_active' => true,
            'used_count' => 0,
            'start_date' => Carbon::today(),
            'end_date' => Carbon::today()->addDays(30)
        ];
    }

    /**
     * Test membuat voucher baru dengan tipe percentage
     * 
     * Skenario:
     * 1. Buat voucher dengan tipe percentage
     * 2. Verifikasi voucher tersimpan di database
     * 3. Verifikasi data voucher sesuai
     */
    public function test_admin_dapat_membuat_voucher_percentage()
    {
        // Act: Buat voucher percentage
        $voucher = Voucher::create($this->voucherData);

        // Assert: Verifikasi voucher tersimpan
        $this->assertInstanceOf(Voucher::class, $voucher, 'Harus mengembalikan instance Voucher');
        $this->assertDatabaseHas('vouchers', [
            'code' => 'DISKON50',
            'name' => 'Diskon 50%',
            'type' => 'percentage',
            'discount_percentage' => '50',
            'is_active' => true
        ]);

        // Verifikasi data voucher
        $this->assertEquals('DISKON50', $voucher->code, 'Kode voucher harus sesuai');
        $this->assertEquals('percentage', $voucher->type, 'Tipe voucher harus percentage');
        $this->assertEquals(50.00, $voucher->discount_percentage, 'Persentase diskon harus 50');
        $this->assertNull($voucher->discount_amount, 'Discount amount harus null untuk tipe percentage');
    }

    /**
     * Test membuat voucher dengan tipe amount
     * 
     * Skenario:
     * 1. Buat voucher dengan tipe amount
     * 2. Verifikasi voucher tersimpan dengan benar
     */
    public function test_admin_dapat_membuat_voucher_fixed_amount()
    {
        // Arrange: Data voucher amount
        $voucherData = [
            'code' => 'POTONGAN100K',
            'name' => 'Potongan 100 Ribu',
            'type' => 'amount',
            'discount_percentage' => null,
            'discount_amount' => 100000,
            'minimum_order_amount' => 500000,
            'is_active' => true,
            'used_count' => 0,
            'start_date' => Carbon::today(),
            'end_date' => Carbon::today()->addDays(15)
        ];

        // Act: Buat voucher amount
        $voucher = Voucher::create($voucherData);

        // Assert: Verifikasi voucher tersimpan
        $this->assertDatabaseHas('vouchers', [
            'code' => 'POTONGAN100K',
            'type' => 'amount',
            'discount_amount' => 100000
        ]);

        $this->assertEquals('amount', $voucher->type, 'Tipe voucher harus amount');
        $this->assertEquals(100000, $voucher->discount_amount, 'Discount amount harus 100000');
        $this->assertNull($voucher->discount_percentage, 'Discount percentage harus null untuk tipe amount');
    }

    /**
     * Test membaca/menampilkan data voucher
     * 
     * Skenario:
     * 1. Buat beberapa voucher di database
     * 2. Ambil semua voucher
     * 3. Verifikasi data voucher yang diambil
     */
    public function test_admin_dapat_membaca_data_voucher()
    {
        // Arrange: Buat beberapa voucher
        $voucher1 = Voucher::create($this->voucherData);

        $voucher2 = Voucher::create([
            'code' => 'NEWUSER20',
            'name' => 'Diskon User Baru 20%',
            'type' => 'percentage',
            'discount_percentage' => 20.00,
            'discount_amount' => null,
            'minimum_order_amount' => 50000,
            'is_active' => true,
            'used_count' => 5,
            'start_date' => Carbon::today()->subDays(10),
            'end_date' => Carbon::today()->addDays(20)
        ]);

        // Act: Ambil semua voucher
        $vouchers = Voucher::all();

        // Assert: Verifikasi data voucher
        $this->assertGreaterThan(0, $vouchers->count(), 'Harus ada voucher');

        // Verifikasi voucher pertama
        $foundVoucher1 = $vouchers->where('code', 'DISKON50')->first();
        $this->assertNotNull($foundVoucher1, 'Voucher DISKON50 harus ditemukan');
        $this->assertEquals('Diskon 50%', $foundVoucher1->name, 'Nama voucher harus sesuai');
        $this->assertEquals(0, $foundVoucher1->used_count, 'Used count harus 0');

        // Verifikasi voucher kedua
        $foundVoucher2 = $vouchers->where('code', 'NEWUSER20')->first();
        $this->assertNotNull($foundVoucher2, 'Voucher NEWUSER20 harus ditemukan');
        $this->assertEquals(5, $foundVoucher2->used_count, 'Used count harus 5');
    }

    /**
     * Test mengupdate data voucher
     * 
     * Skenario:
     * 1. Buat voucher baru
     * 2. Update data voucher
     * 3. Verifikasi perubahan tersimpan di database
     */
    public function test_admin_dapat_mengupdate_voucher()
    {
        // Arrange: Buat voucher
        $voucher = Voucher::create($this->voucherData);

        // Act: Update voucher
        $updatedData = [
            'name' => 'Diskon 50% Updated',
            'discount_percentage' => 60.00,
            'minimum_order_amount' => 150000,
            'end_date' => Carbon::today()->addDays(45)
        ];
        $voucher->update($updatedData);

        // Assert: Verifikasi perubahan tersimpan
        $voucher->refresh();
        $this->assertEquals('Diskon 50% Updated', $voucher->name, 'Nama voucher harus terupdate');
        $this->assertEquals(60.00, $voucher->discount_percentage, 'Persentase diskon harus terupdate');
        $this->assertEquals(150000, $voucher->minimum_order_amount, 'Minimum order amount harus terupdate');

        // Verifikasi di database
        $this->assertDatabaseHas('vouchers', [
            'code' => 'DISKON50',
            'name' => 'Diskon 50% Updated',
            'discount_percentage' => 60.00,
            'minimum_order_amount' => 150000
        ]);
    }

    /**
     * Test soft delete voucher
     * 
     * Skenario:
     * 1. Buat voucher baru
     * 2. Hapus voucher (soft delete)
     * 3. Verifikasi voucher tidak muncul di query normal
     * 4. Verifikasi voucher masih ada dengan withTrashed()
     */
    public function test_admin_dapat_menghapus_voucher_soft_delete()
    {
        // Arrange: Buat voucher
        $voucher = Voucher::create($this->voucherData);
        $voucherId = $voucher->voucher_id;

        // Act: Hapus voucher (soft delete)
        $voucher->delete();

        // Assert: Verifikasi voucher tidak muncul di query normal
        $this->assertNull(Voucher::find($voucherId), 'Voucher tidak boleh ditemukan setelah dihapus');

        // Verifikasi voucher masih ada dengan withTrashed()
        $trashedVoucher = Voucher::withTrashed()->find($voucherId);
        $this->assertNotNull($trashedVoucher, 'Voucher harus masih ada dengan withTrashed()');
        $this->assertNotNull($trashedVoucher->deleted_at, 'deleted_at harus ter-set');
    }

    /**
     * Test restore voucher yang sudah dihapus
     * 
     * Skenario:
     * 1. Buat dan hapus voucher
     * 2. Restore voucher
     * 3. Verifikasi voucher kembali muncul di query normal
     */
    public function test_admin_dapat_restore_voucher_yang_dihapus()
    {
        // Arrange: Buat dan hapus voucher
        $voucher = Voucher::create($this->voucherData);
        $voucherId = $voucher->voucher_id;
        $voucher->delete();

        // Verifikasi voucher terhapus
        $this->assertNull(Voucher::find($voucherId), 'Voucher harus terhapus');

        // Act: Restore voucher
        $trashedVoucher = Voucher::withTrashed()->find($voucherId);
        $trashedVoucher->restore();

        // Assert: Verifikasi voucher kembali muncul
        $restoredVoucher = Voucher::find($voucherId);
        $this->assertNotNull($restoredVoucher, 'Voucher harus kembali muncul setelah restore');
        $this->assertNull($restoredVoucher->deleted_at, 'deleted_at harus null setelah restore');
        $this->assertEquals('DISKON50', $restoredVoucher->code, 'Data voucher harus sama setelah restore');
    }



    /**
     * Test scope valid dan invalid
     * 
     * Skenario:
     * 1. Buat voucher dengan status berbeda
     * 2. Test scope valid() dan invalid()
     * 3. Verifikasi hasil query scope
     */
    public function test_scope_valid_dan_invalid()
    {
        // Arrange: Buat voucher dengan periode berbeda
        $validVoucher = Voucher::create($this->voucherData); // Valid

        $expiredVoucher = Voucher::create([
            'code' => 'EXPIRED',
            'name' => 'Voucher Melewati_jatuh_tempo',
            'type' => 'percentage',
            'discount_percentage' => 30.00,
            'discount_amount' => null,
            'minimum_order_amount' => 100000,
            'is_active' => true,
            'used_count' => 0,
            'start_date' => Carbon::today()->subDays(30),
            'end_date' => Carbon::today()->subDays(1) // Melewati_jatuh_tempo
        ]);

        $futureVoucher = Voucher::create([
            'code' => 'FUTURE',
            'name' => 'Voucher Future',
            'type' => 'percentage',
            'discount_percentage' => 25.00,
            'discount_amount' => null,
            'minimum_order_amount' => 100000,
            'is_active' => true,
            'used_count' => 0,
            'start_date' => Carbon::today()->addDays(1), // Future
            'end_date' => Carbon::today()->addDays(30)
        ]);

        // Act: Test scope dengan filter berdasarkan voucher yang dibuat dalam test
        $createdVoucherIds = [$validVoucher->voucher_id, $expiredVoucher->voucher_id, $futureVoucher->voucher_id];
        $validVouchers = Voucher::valid()->whereIn('voucher_id', $createdVoucherIds)->get();
        $invalidVouchers = Voucher::invalid()->whereIn('voucher_id', $createdVoucherIds)->get();

        // Assert: Verifikasi hasil scope
        $this->assertGreaterThanOrEqual(1, $validVouchers->count(), 'Harus ada minimal 1 voucher valid');
        $this->assertGreaterThanOrEqual(2, $invalidVouchers->count(), 'Harus ada minimal 2 voucher invalid');

        // Verifikasi voucher valid berdasarkan yang dibuat dalam test
        $validCodes = $validVouchers->pluck('code')->toArray();
        $this->assertContains('DISKON50', $validCodes, 'Harus mengandung voucher DISKON50');

        $invalidCodes = $invalidVouchers->pluck('code')->toArray();
        $this->assertContains('EXPIRED', $invalidCodes, 'Harus mengandung voucher EXPIRED');
        $this->assertContains('FUTURE', $invalidCodes, 'Harus mengandung voucher FUTURE');
    }

    /**
     * Test mengaktifkan/menonaktifkan voucher
     * 
     * Skenario:
     * 1. Buat voucher aktif
     * 2. Nonaktifkan voucher
     * 3. Aktifkan kembali voucher
     * 4. Verifikasi status is_active
     */
    public function test_admin_dapat_mengaktifkan_menonaktifkan_voucher()
    {
        // Arrange: Buat voucher aktif
        $voucher = Voucher::create($this->voucherData);
        $this->assertTrue($voucher->is_active, 'Voucher harus aktif saat dibuat');

        // Act: Nonaktifkan voucher
        $voucher->update(['is_active' => false]);

        // Assert: Verifikasi voucher nonaktif
        $voucher->refresh();
        $this->assertFalse($voucher->is_active, 'Voucher harus nonaktif');

        // Act: Aktifkan kembali voucher
        $voucher->update(['is_active' => true]);

        // Assert: Verifikasi voucher aktif kembali
        $voucher->refresh();
        $this->assertTrue($voucher->is_active, 'Voucher harus aktif kembali');
    }

    /**
     * Test update used count voucher
     * 
     * Skenario:
     * 1. Buat voucher dengan used_count 0
     * 2. Update used_count (simulasi penggunaan)
     * 3. Verifikasi used_count terupdate
     */
    public function test_admin_dapat_mengelola_used_count_voucher()
    {
        // Arrange: Buat voucher dengan used_count 0
        $voucher = Voucher::create($this->voucherData);
        $this->assertEquals(0, $voucher->used_count, 'Used count awal harus 0');

        // Act: Update used_count (simulasi penggunaan)
        $voucher->update(['used_count' => $voucher->used_count + 1]);

        // Assert: Verifikasi used_count terupdate
        $voucher->refresh();
        $this->assertEquals(1, $voucher->used_count, 'Used count harus menjadi 1');

        // Act: Tambah lagi used_count
        $voucher->update(['used_count' => $voucher->used_count + 2]);

        // Assert: Verifikasi used_count bertambah
        $voucher->refresh();
        $this->assertEquals(3, $voucher->used_count, 'Used count harus menjadi 3');

        // Verifikasi di database
        $this->assertDatabaseHas('vouchers', [
            'code' => 'DISKON50',
            'used_count' => 3
        ]);
    }

    /**
     * Test filter voucher berdasarkan tipe
     * 
     * Skenario:
     * 1. Buat voucher dengan tipe berbeda
     * 2. Filter voucher berdasarkan tipe
     * 3. Verifikasi hasil filter
     */
    public function test_filter_voucher_berdasarkan_tipe()
    {
        // Arrange: Buat voucher dengan tipe berbeda
        $percentageVoucher = Voucher::create($this->voucherData); // Percentage

        $amountVoucher = Voucher::create([
            'code' => 'POTONGAN50K',
            'name' => 'Potongan 50 Ribu',
            'type' => 'amount',
            'discount_percentage' => null,
            'discount_amount' => 50000,
            'minimum_order_amount' => 200000,
            'is_active' => true,
            'used_count' => 0,
            'start_date' => Carbon::today(),
            'end_date' => Carbon::today()->addDays(30)
        ]);

        // Act: Filter voucher berdasarkan tipe dari voucher yang dibuat dalam test
        $createdVoucherIds = [$percentageVoucher->voucher_id, $amountVoucher->voucher_id];
        $percentageVouchers = Voucher::where('type', 'percentage')->whereIn('voucher_id', $createdVoucherIds)->get();
        $amountVouchers = Voucher::where('type', 'amount')->whereIn('voucher_id', $createdVoucherIds)->get();

        // Assert: Verifikasi hasil filter
        $this->assertGreaterThanOrEqual(1, $percentageVouchers->count(), 'Harus ada minimal 1 voucher percentage');
        $this->assertGreaterThanOrEqual(1, $amountVouchers->count(), 'Harus ada minimal 1 voucher amount');

        $this->assertEquals('DISKON50', $percentageVouchers->first()->code, 'Voucher percentage harus DISKON50');
        $this->assertEquals('POTONGAN50K', $amountVouchers->first()->code, 'Voucher amount harus POTONGAN50K');
    }

    /**
     * Test pencarian voucher berdasarkan kode
     * 
     * Skenario:
     * 1. Buat beberapa voucher
     * 2. Cari voucher berdasarkan kode
     * 3. Verifikasi hasil pencarian
     */
    public function test_pencarian_voucher_berdasarkan_kode()
    {
        // Arrange: Buat beberapa voucher
        Voucher::create($this->voucherData);

        Voucher::create([
            'code' => 'DISKON30',
            'name' => 'Diskon 30%',
            'type' => 'percentage',
            'discount_percentage' => 30.00,
            'discount_amount' => null,
            'minimum_order_amount' => 75000,
            'is_active' => true,
            'used_count' => 0,
            'start_date' => Carbon::today(),
            'end_date' => Carbon::today()->addDays(30)
        ]);

        Voucher::create([
            'code' => 'NEWBIE10',
            'name' => 'Diskon Newbie 10%',
            'type' => 'percentage',
            'discount_percentage' => 10.00,
            'discount_amount' => null,
            'minimum_order_amount' => 50000,
            'is_active' => true,
            'used_count' => 0,
            'start_date' => Carbon::today(),
            'end_date' => Carbon::today()->addDays(30)
        ]);

        // Act: Cari voucher yang mengandung kata 'DISKON'
        $diskonVouchers = Voucher::where('code', 'like', '%DISKON%')->get();

        // Assert: Verifikasi hasil pencarian
        $this->assertGreaterThanOrEqual(2, $diskonVouchers->count(), 'Harus ditemukan minimal 2 voucher dengan kode DISKON');

        $voucherCodes = $diskonVouchers->pluck('code')->toArray();
        $this->assertContains('DISKON50', $voucherCodes, 'Harus mengandung DISKON50');
        $this->assertContains('DISKON30', $voucherCodes, 'Harus mengandung DISKON30');
    }

    /**
     * Test filter voucher berdasarkan status aktif
     * 
     * Skenario:
     * 1. Buat voucher aktif dan nonaktif
     * 2. Filter voucher berdasarkan status aktif
     * 3. Verifikasi hasil filter
     */
    public function test_filter_voucher_berdasarkan_status_aktif()
    {
        // Arrange: Buat voucher aktif
        $activeVoucher = Voucher::create($this->voucherData);

        // Buat voucher nonaktif
        $inactiveVoucher = Voucher::create([
            'code' => 'INACTIVE',
            'name' => 'Voucher Nonaktif',
            'type' => 'percentage',
            'discount_percentage' => 20.00,
            'discount_amount' => null,
            'minimum_order_amount' => 100000,
            'is_active' => false, // Nonaktif
            'used_count' => 0,
            'start_date' => Carbon::today(),
            'end_date' => Carbon::today()->addDays(30)
        ]);

        // Act: Filter voucher berdasarkan status dari voucher yang dibuat dalam test
        $createdVoucherIds = [$activeVoucher->voucher_id, $inactiveVoucher->voucher_id];
        $activeVouchers = Voucher::where('is_active', true)->whereIn('voucher_id', $createdVoucherIds)->get();
        $inactiveVouchers = Voucher::where('is_active', false)->whereIn('voucher_id', $createdVoucherIds)->get();

        // Assert: Verifikasi hasil filter
        $this->assertGreaterThanOrEqual(1, $activeVouchers->count(), 'Harus ada minimal 1 voucher aktif');
        $this->assertGreaterThanOrEqual(1, $inactiveVouchers->count(), 'Harus ada minimal 1 voucher nonaktif');

        $this->assertEquals('DISKON50', $activeVouchers->first()->code, 'Voucher aktif harus DISKON50');
        $this->assertEquals('INACTIVE', $inactiveVouchers->first()->code, 'Voucher nonaktif harus INACTIVE');
    }
}
