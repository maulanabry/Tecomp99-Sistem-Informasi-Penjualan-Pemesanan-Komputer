<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\OrderProduct;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;

/**
 * Test Unit untuk Manajemen Pemesanan Produk
 * 
 * Test ini mencakup user story:
 * - Sebagai admin, saya ingin mengelola pemesanan produk sehingga dapat memproses pesanan pembelian produk
 */
class OrderProductTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test membuat order produk baru
     * 
     * Skenario:
     * 1. Buat customer dan produk
     * 2. Buat order produk baru
     * 3. Verifikasi order tersimpan di database
     */
    public function test_admin_dapat_membuat_order_produk_baru()
    {
        // Arrange: Buat customer
        $customer = Customer::create([
            'customer_id' => 'CST999998001',
            'name' => 'Order Customer',
            'email' => 'order@example.com',
            'contact' => '081234567890',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Arrange: Buat order produk
        $orderProduct = OrderProduct::create([
            'order_product_id' => 'OP999998001',
            'customer_id' => $customer->customer_id,
            'status_order' => 'menunggu',
            'status_payment' => 'belum_dibayar',
            'sub_total' => 100000,
            'discount_amount' => 0,
            'grand_total' => 115000,
            'shipping_cost' => 15000,
            'type' => 'pengiriman',
            'paid_amount' => 0,
            'remaining_balance' => 115000
        ]);

        // Assert: Verifikasi order tersimpan
        $this->assertInstanceOf(OrderProduct::class, $orderProduct, 'Harus mengembalikan instance OrderProduct');
        $this->assertEquals('OP999998001', $orderProduct->order_product_id, 'Order ID harus sesuai');
        $this->assertEquals($customer->customer_id, $orderProduct->customer_id, 'Customer ID harus sesuai');
        $this->assertEquals(100000, $orderProduct->sub_total, 'Sub total harus sesuai');
    }

    /**
     * Test membaca data order produk
     * 
     * Skenario:
     * 1. Buat order produk
     * 2. Ambil order berdasarkan ID
     * 3. Verifikasi data order yang diambil
     */
    public function test_admin_dapat_membaca_data_order_produk()
    {
        // Arrange: Buat customer dan order
        $customer = Customer::create([
            'customer_id' => 'CST999998002',
            'name' => 'Read Order Customer',
            'email' => 'readorder@example.com',
            'contact' => '081234567891',
            'gender' => 'wanita',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        $orderProduct = OrderProduct::create([
            'order_product_id' => 'OP999998002',
            'customer_id' => $customer->customer_id,
            'status_order' => 'diproses',
            'status_payment' => 'down_payment',
            'sub_total' => 200000,
            'discount_amount' => 10000,
            'grand_total' => 210000,
            'shipping_cost' => 20000,
            'type' => 'pengiriman',
            'paid_amount' => 50000,
            'remaining_balance' => 160000
        ]);

        // Act: Ambil order berdasarkan ID
        $foundOrder = OrderProduct::find('OP999998002');

        // Assert: Verifikasi data order
        $this->assertNotNull($foundOrder, 'Order harus ditemukan');
        $this->assertEquals('diproses', $foundOrder->status_order, 'Status order harus sesuai');
        $this->assertEquals(200000, $foundOrder->sub_total, 'Sub total harus sesuai');
    }

    /**
     * Test mengupdate status order produk
     * 
     * Skenario:
     * 1. Buat order produk
     * 2. Update status order
     * 3. Verifikasi perubahan tersimpan
     */
    public function test_admin_dapat_mengupdate_status_order_produk()
    {
        // Arrange: Buat customer dan order
        $customer = Customer::create([
            'customer_id' => 'CST999998003',
            'name' => 'Update Order Customer',
            'email' => 'updateorder@example.com',
            'contact' => '081234567892',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        $orderProduct = OrderProduct::create([
            'order_product_id' => 'OP999998003',
            'customer_id' => $customer->customer_id,
            'status_order' => 'menunggu',
            'status_payment' => 'belum_dibayar',
            'sub_total' => 150000,
            'discount_amount' => 0,
            'grand_total' => 165000,
            'shipping_cost' => 15000,
            'type' => 'pengiriman',
            'paid_amount' => 0,
            'remaining_balance' => 165000
        ]);

        // Act: Update status order
        $orderProduct->update([
            'status_order' => 'diproses'
        ]);

        // Assert: Verifikasi perubahan tersimpan
        $orderProduct->refresh();
        $this->assertEquals('diproses', $orderProduct->status_order, 'Status order harus terupdate');
    }

    /**
     * Test pencarian order produk berdasarkan status
     * 
     * Skenario:
     * 1. Buat beberapa order dengan status berbeda
     * 2. Cari order berdasarkan status
     * 3. Verifikasi hasil pencarian
     */
    public function test_pencarian_order_produk_berdasarkan_status()
    {
        // Arrange: Buat customer
        $customer = Customer::create([
            'customer_id' => 'CST999998004',
            'name' => 'Search Order Customer',
            'email' => 'searchorder@example.com',
            'contact' => '081234567893',
            'gender' => 'wanita',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Buat order dengan status menunggu
        OrderProduct::create([
            'order_product_id' => 'OP999998004',
            'customer_id' => $customer->customer_id,
            'status_order' => 'menunggu',
            'status_payment' => 'belum_dibayar',
            'sub_total' => 100000,
            'discount_amount' => 0,
            'grand_total' => 110000,
            'shipping_cost' => 10000,
            'type' => 'pengiriman',
            'paid_amount' => 0,
            'remaining_balance' => 110000
        ]);

        // Act: Cari order berdasarkan status menunggu
        $pendingOrders = OrderProduct::where('status_order', 'menunggu')
            ->where('order_product_id', 'OP999998004')
            ->get();

        // Assert: Verifikasi hasil pencarian
        $this->assertGreaterThan(0, $pendingOrders->count(), 'Harus ditemukan order dengan status menunggu');
        $this->assertEquals('menunggu', $pendingOrders->first()->status_order, 'Status order harus menunggu');
    }

    /**
     * Test soft delete order produk
     * 
     * Skenario:
     * 1. Buat order produk
     * 2. Hapus order (soft delete)
     * 3. Verifikasi order tidak muncul di query normal
     */
    public function test_admin_dapat_menghapus_order_produk_soft_delete()
    {
        // Arrange: Buat customer dan order
        $customer = Customer::create([
            'customer_id' => 'CST999998005',
            'name' => 'Delete Order Customer',
            'email' => 'deleteorder@example.com',
            'contact' => '081234567894',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        $orderProduct = OrderProduct::create([
            'order_product_id' => 'OP999998005',
            'customer_id' => $customer->customer_id,
            'status_order' => 'dibatalkan',
            'status_payment' => 'dibatalkan',
            'sub_total' => 75000,
            'discount_amount' => 0,
            'grand_total' => 85000,
            'shipping_cost' => 10000,
            'type' => 'pengiriman',
            'paid_amount' => 0,
            'remaining_balance' => 85000
        ]);

        $orderId = $orderProduct->order_product_id;

        // Act: Hapus order (soft delete)
        $orderProduct->delete();

        // Assert: Verifikasi order tidak muncul di query normal
        $this->assertNull(OrderProduct::find($orderId), 'Order tidak boleh ditemukan setelah dihapus');

        // Verifikasi order masih ada dengan withTrashed()
        $trashedOrder = OrderProduct::withTrashed()->find($orderId);
        $this->assertNotNull($trashedOrder, 'Order harus masih ada dengan withTrashed()');
    }
}
