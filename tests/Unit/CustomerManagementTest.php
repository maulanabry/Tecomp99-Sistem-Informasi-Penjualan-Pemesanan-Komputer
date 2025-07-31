<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Customer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

/**
 * Test Unit untuk Manajemen Informasi Pelanggan
 * 
 * Test ini mencakup user story:
 * - Sebagai admin, saya ingin mengelola informasi pelanggan sehingga dapat memantau data pelanggan
 */
class CustomerManagementTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test membuat customer baru
     * 
     * Skenario:
     * 1. Buat customer baru dengan data valid
     * 2. Verifikasi customer tersimpan di database
     */
    public function test_admin_dapat_membuat_customer_baru()
    {
        // Arrange: Data customer untuk testing
        $customerData = [
            'customer_id' => 'CST999999001',
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'contact' => '081234567890',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0,
            'last_active' => Carbon::now(),
            'email_verified_at' => Carbon::now()
        ];

        // Act: Buat customer baru
        $customer = Customer::create($customerData);

        // Assert: Verifikasi customer tersimpan
        $this->assertInstanceOf(Customer::class, $customer, 'Harus mengembalikan instance Customer');
        $this->assertEquals('CST999999001', $customer->customer_id, 'Customer ID harus sesuai');
        $this->assertEquals('Test Customer', $customer->name, 'Nama customer harus sesuai');
    }

    /**
     * Test membaca data customer
     * 
     * Skenario:
     * 1. Buat customer di database
     * 2. Ambil customer berdasarkan ID
     * 3. Verifikasi data customer yang diambil
     */
    public function test_admin_dapat_membaca_data_customer()
    {
        // Arrange: Buat customer
        $customer = Customer::create([
            'customer_id' => 'CST999999002',
            'name' => 'Read Test Customer',
            'email' => 'read@example.com',
            'contact' => '081234567891',
            'gender' => 'wanita',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 50
        ]);

        // Act: Ambil customer berdasarkan ID
        $foundCustomer = Customer::find('CST999999002');

        // Assert: Verifikasi data customer
        $this->assertNotNull($foundCustomer, 'Customer harus ditemukan');
        $this->assertEquals('Read Test Customer', $foundCustomer->name, 'Nama customer harus sesuai');
        $this->assertEquals(50, $foundCustomer->total_points, 'Total points harus 50');
    }

    /**
     * Test mengupdate data customer
     * 
     * Skenario:
     * 1. Buat customer baru
     * 2. Update data customer
     * 3. Verifikasi perubahan tersimpan di database
     */
    public function test_admin_dapat_mengupdate_customer()
    {
        // Arrange: Buat customer
        $customer = Customer::create([
            'customer_id' => 'CST999999003',
            'name' => 'Update Test Customer',
            'email' => 'update@example.com',
            'contact' => '081234567892',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Act: Update customer
        $customer->update([
            'name' => 'Updated Customer Name',
            'total_points' => 100
        ]);

        // Assert: Verifikasi perubahan tersimpan
        $customer->refresh();
        $this->assertEquals('Updated Customer Name', $customer->name, 'Nama customer harus terupdate');
        $this->assertEquals(100, $customer->total_points, 'Total points harus terupdate');
    }

    /**
     * Test soft delete customer
     * 
     * Skenario:
     * 1. Buat customer baru
     * 2. Hapus customer (soft delete)
     * 3. Verifikasi customer tidak muncul di query normal
     */
    public function test_admin_dapat_menghapus_customer_soft_delete()
    {
        // Arrange: Buat customer
        $customer = Customer::create([
            'customer_id' => 'CST999999004',
            'name' => 'Delete Test Customer',
            'email' => 'delete@example.com',
            'contact' => '081234567893',
            'gender' => 'wanita',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        $customerId = $customer->customer_id;

        // Act: Hapus customer (soft delete)
        $customer->delete();

        // Assert: Verifikasi customer tidak muncul di query normal
        $this->assertNull(Customer::find($customerId), 'Customer tidak boleh ditemukan setelah dihapus');

        // Verifikasi customer masih ada dengan withTrashed()
        $trashedCustomer = Customer::withTrashed()->find($customerId);
        $this->assertNotNull($trashedCustomer, 'Customer harus masih ada dengan withTrashed()');
    }

    /**
     * Test pencarian customer berdasarkan nama
     * 
     * Skenario:
     * 1. Buat customer
     * 2. Cari customer berdasarkan nama
     * 3. Verifikasi hasil pencarian
     */
    public function test_pencarian_customer_berdasarkan_nama()
    {
        // Arrange: Buat customer dengan nama unik
        Customer::create([
            'customer_id' => 'CST999999005',
            'name' => 'SearchableCustomer',
            'email' => 'search@example.com',
            'contact' => '081234567894',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Act: Cari customer berdasarkan nama
        $foundCustomers = Customer::where('name', 'like', '%Searchable%')->get();

        // Assert: Verifikasi hasil pencarian
        $this->assertGreaterThan(0, $foundCustomers->count(), 'Harus ditemukan customer dengan nama Searchable');
    }
}
