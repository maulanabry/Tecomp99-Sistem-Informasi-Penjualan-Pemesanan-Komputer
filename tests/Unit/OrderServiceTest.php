<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\OrderService;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;
use App\Observers\OrderServiceObserver;

/**
 * Test Unit untuk Manajemen Pemesanan Servis
 * 
 * Test ini mencakup user story:
 * - Sebagai admin, saya ingin mengelola pemesanan servis sehingga dapat memproses pesanan servis pelanggan
 */
class OrderServiceTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Setup yang dijalankan sebelum setiap test
     * Disable observer untuk menghindari route error dalam unit test
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Disable OrderService observer untuk unit testing
        OrderService::unsetEventDispatcher();
    }

    /**
     * Test membuat order servis baru
     * 
     * Skenario:
     * 1. Buat customer
     * 2. Buat order servis baru
     * 3. Verifikasi order tersimpan di database
     */
    public function test_admin_dapat_membuat_order_servis_baru()
    {
        // Arrange: Buat customer
        $customer = Customer::create([
            'customer_id' => 'CST999997001',
            'name' => 'Service Order Customer',
            'email' => 'serviceorder@example.com',
            'contact' => '081234567890',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Arrange: Buat order servis
        $orderService = OrderService::create([
            'order_service_id' => 'OS999997001',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Menunggu',
            'status_payment' => 'belum_dibayar',
            'type' => 'reguler',
            'device' => 'Laptop tidak bisa menyala',
            'complaints' => 'Laptop mati total, tidak ada tanda kehidupan',
            'sub_total' => 250000,
            'discount_amount' => 0,
            'grand_total' => 250000,
            'paid_amount' => 0,
            'remaining_balance' => 250000
        ]);

        // Assert: Verifikasi order tersimpan
        $this->assertInstanceOf(OrderService::class, $orderService, 'Harus mengembalikan instance OrderService');
        $this->assertEquals('OS999997001', $orderService->order_service_id, 'Order ID harus sesuai');
        $this->assertEquals($customer->customer_id, $orderService->customer_id, 'Customer ID harus sesuai');
        $this->assertEquals(250000, $orderService->sub_total, 'Sub total harus sesuai');
        $this->assertEquals('reguler', $orderService->type, 'Service type harus sesuai');
    }

    /**
     * Test membaca data order servis
     * 
     * Skenario:
     * 1. Buat order servis
     * 2. Ambil order berdasarkan ID
     * 3. Verifikasi data order yang diambil
     */
    public function test_admin_dapat_membaca_data_order_servis()
    {
        // Arrange: Buat customer dan order
        $customer = Customer::create([
            'customer_id' => 'CST999997002',
            'name' => 'Read Service Order Customer',
            'email' => 'readserviceorder@example.com',
            'contact' => '081234567891',
            'gender' => 'wanita',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        $orderService = OrderService::create([
            'order_service_id' => 'OS999997002',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Diproses',
            'status_payment' => 'down_payment',
            'type' => 'onsite',
            'device' => 'Komputer Desktop',
            'complaints' => 'Komputer sering restart sendiri tanpa sebab',
            'sub_total' => 300000,
            'discount_amount' => 25000,
            'grand_total' => 275000,
            'paid_amount' => 100000,
            'remaining_balance' => 175000
        ]);

        // Act: Ambil order berdasarkan ID
        $foundOrder = OrderService::find('OS999997002');

        // Assert: Verifikasi data order
        $this->assertNotNull($foundOrder, 'Order harus ditemukan');
        $this->assertEquals('Diproses', $foundOrder->status_order, 'Status order harus sesuai');
        $this->assertEquals(300000, $foundOrder->sub_total, 'Sub total harus sesuai');
        $this->assertEquals('onsite', $foundOrder->type, 'Service type harus sesuai');
    }

    /**
     * Test mengupdate status order servis
     * 
     * Skenario:
     * 1. Buat order servis
     * 2. Update status order
     * 3. Verifikasi perubahan tersimpan
     */
    public function test_admin_dapat_mengupdate_status_order_servis()
    {
        // Arrange: Buat customer dan order
        $customer = Customer::create([
            'customer_id' => 'CST999997003',
            'name' => 'Update Service Order Customer',
            'email' => 'updateserviceorder@example.com',
            'contact' => '081234567892',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        $orderService = OrderService::create([
            'order_service_id' => 'OS999997003',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Menunggu',
            'status_payment' => 'belum_dibayar',
            'type' => 'reguler',
            'device' => 'Laptop',
            'complaints' => 'Keyboard tidak berfungsi dengan baik',
            'sub_total' => 180000,
            'discount_amount' => 0,
            'grand_total' => 180000,
            'paid_amount' => 0,
            'remaining_balance' => 180000
        ]);

        // Act: Update status order
        $orderService->update([
            'status_order' => 'Diproses'
        ]);

        // Assert: Verifikasi perubahan tersimpan
        $orderService->refresh();
        $this->assertEquals('Diproses', $orderService->status_order, 'Status order harus terupdate');
    }

    /**
     * Test pencarian order servis berdasarkan tipe servis
     * 
     * Skenario:
     * 1. Buat order dengan tipe servis berbeda
     * 2. Cari order berdasarkan tipe servis
     * 3. Verifikasi hasil pencarian
     */
    public function test_pencarian_order_servis_berdasarkan_tipe()
    {
        // Arrange: Buat customer
        $customer = Customer::create([
            'customer_id' => 'CST999997004',
            'name' => 'Search Service Order Customer',
            'email' => 'searchserviceorder@example.com',
            'contact' => '081234567893',
            'gender' => 'wanita',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Buat order dengan tipe onsite
        OrderService::create([
            'order_service_id' => 'OS999997004',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Menunggu',
            'status_payment' => 'belum_dibayar',
            'type' => 'onsite',
            'device' => 'Server',
            'complaints' => 'Urgent repair needed for server',
            'sub_total' => 400000,
            'discount_amount' => 0,
            'grand_total' => 400000,
            'paid_amount' => 0,
            'remaining_balance' => 400000
        ]);

        // Act: Cari order berdasarkan tipe onsite
        $onsiteOrders = OrderService::where('type', 'onsite')
            ->where('order_service_id', 'OS999997004')
            ->get();

        // Assert: Verifikasi hasil pencarian
        $this->assertGreaterThan(0, $onsiteOrders->count(), 'Harus ditemukan order dengan tipe onsite');
        $this->assertEquals('onsite', $onsiteOrders->first()->type, 'Service type harus onsite');
    }

    /**
     * Test filter order servis berdasarkan status
     * 
     * Skenario:
     * 1. Buat order dengan status berbeda
     * 2. Filter order berdasarkan status
     * 3. Verifikasi hasil filter
     */
    public function test_filter_order_servis_berdasarkan_status()
    {
        // Arrange: Buat customer
        $customer = Customer::create([
            'customer_id' => 'CST999997005',
            'name' => 'Filter Service Order Customer',
            'email' => 'filterserviceorder@example.com',
            'contact' => '081234567894',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Buat order dengan status Selesai
        OrderService::create([
            'order_service_id' => 'OS999997005',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Selesai',
            'status_payment' => 'lunas',
            'type' => 'reguler',
            'device' => 'Laptop',
            'complaints' => 'Service completed successfully',
            'sub_total' => 150000,
            'discount_amount' => 15000,
            'grand_total' => 135000,
            'paid_amount' => 135000,
            'remaining_balance' => 0
        ]);

        // Act: Filter order berdasarkan status Selesai
        $completedOrders = OrderService::where('status_order', 'Selesai')
            ->where('order_service_id', 'OS999997005')
            ->get();

        // Assert: Verifikasi hasil filter
        $this->assertGreaterThan(0, $completedOrders->count(), 'Harus ada order dengan status Selesai');
        $this->assertEquals('Selesai', $completedOrders->first()->status_order, 'Status order harus Selesai');
    }

    /**
     * Test soft delete order servis
     * 
     * Skenario:
     * 1. Buat order servis
     * 2. Hapus order (soft delete)
     * 3. Verifikasi order tidak muncul di query normal
     */
    public function test_admin_dapat_menghapus_order_servis_soft_delete()
    {
        // Arrange: Buat customer dan order
        $customer = Customer::create([
            'customer_id' => 'CST999997006',
            'name' => 'Delete Service Order Customer',
            'email' => 'deleteserviceorder@example.com',
            'contact' => '081234567895',
            'gender' => 'wanita',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        $orderService = OrderService::create([
            'order_service_id' => 'OS999997006',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Dibatalkan',
            'status_payment' => 'dibatalkan',
            'type' => 'reguler',
            'device' => 'Laptop',
            'complaints' => 'Order cancelled by customer',
            'sub_total' => 100000,
            'discount_amount' => 0,
            'grand_total' => 100000,
            'paid_amount' => 0,
            'remaining_balance' => 100000
        ]);

        $orderId = $orderService->order_service_id;

        // Act: Hapus order (soft delete)
        $orderService->delete();

        // Assert: Verifikasi order tidak muncul di query normal
        $this->assertNull(OrderService::find($orderId), 'Order tidak boleh ditemukan setelah dihapus');

        // Verifikasi order masih ada dengan withTrashed()
        $trashedOrder = OrderService::withTrashed()->find($orderId);
        $this->assertNotNull($trashedOrder, 'Order harus masih ada dengan withTrashed()');
    }
}
