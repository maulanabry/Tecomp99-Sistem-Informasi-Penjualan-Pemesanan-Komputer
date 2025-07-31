<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\PaymentDetail;
use App\Models\OrderProduct;
use App\Models\OrderService;
use App\Models\Customer;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;
use App\Observers\PaymentDetailObserver;

/**
 * Test Unit untuk Konfirmasi Pembayaran (Simplified)
 * 
 * Test ini mencakup user story:
 * - Sebagai admin, saya ingin mengkonfirmasi pembayaran sehingga dapat memverifikasi pemesanan pelanggan
 */
class PaymentConfirmationTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Setup yang dijalankan sebelum setiap test
     * Disable observer untuk menghindari route error dalam unit test
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Disable semua observers untuk unit testing
        PaymentDetail::unsetEventDispatcher();
        OrderProduct::unsetEventDispatcher();
        OrderService::unsetEventDispatcher();
    }

    /**
     * Test membuat payment detail baru untuk order produk
     * 
     * Skenario:
     * 1. Buat customer dan order produk
     * 2. Buat payment detail untuk order produk
     * 3. Verifikasi payment tersimpan di database
     */
    public function test_admin_dapat_membuat_payment_detail_order_produk()
    {
        // Arrange: Buat customer
        $customer = Customer::create([
            'customer_id' => 'CST999991001',
            'name' => 'Payment Customer',
            'email' => 'payment@example.com',
            'contact' => '081234567890',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Buat order produk
        $orderProduct = OrderProduct::create([
            'order_product_id' => 'OP999991001',
            'customer_id' => $customer->customer_id,
            'status_order' => 'menunggu',
            'status_payment' => 'belum_dibayar',
            'sub_total' => 1000000,
            'discount_amount' => 0,
            'grand_total' => 1050000,
            'shipping_cost' => 50000,
            'type' => 'pengiriman',
            'paid_amount' => 0,
            'remaining_balance' => 1050000
        ]);

        // Act: Buat payment detail
        $payment = PaymentDetail::create([
            'payment_id' => 'PAY999991001',
            'order_product_id' => $orderProduct->order_product_id,
            'method' => 'Tunai',
            'amount' => 500000,
            'cash_received' => 500000,
            'change_returned' => 0,
            'name' => 'admin',
            'status' => 'menunggu',
            'payment_type' => 'down_payment',
            'order_type' => 'produk'
        ]);

        // Assert: Verifikasi payment tersimpan
        $this->assertInstanceOf(PaymentDetail::class, $payment, 'Harus mengembalikan instance PaymentDetail');
        $this->assertEquals('PAY999991001', $payment->payment_id, 'Payment ID harus sesuai');
        $this->assertEquals($orderProduct->order_product_id, $payment->order_product_id, 'Order product ID harus sesuai');
        $this->assertEquals(500000, $payment->amount, 'Amount harus sesuai');
        $this->assertEquals('menunggu', $payment->status, 'Status harus menunggu');
    }

    /**
     * Test konfirmasi pembayaran (update status menjadi dibayar)
     * 
     * Skenario:
     * 1. Buat payment dengan status pending
     * 2. Konfirmasi pembayaran (update status menjadi dibayar)
     * 3. Verifikasi status payment terupdate
     */
    public function test_admin_dapat_mengkonfirmasi_pembayaran()
    {
        // Arrange: Buat customer dan order
        $customer = Customer::create([
            'customer_id' => 'CST999991002',
            'name' => 'Confirm Payment Customer',
            'email' => 'confirm@example.com',
            'contact' => '081234567891',
            'gender' => 'wanita',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        $orderProduct = OrderProduct::create([
            'order_product_id' => 'OP999991002',
            'customer_id' => $customer->customer_id,
            'status_order' => 'menunggu',
            'status_payment' => 'belum_dibayar',
            'sub_total' => 800000,
            'discount_amount' => 0,
            'grand_total' => 850000,
            'shipping_cost' => 50000,
            'type' => 'pengiriman',
            'paid_amount' => 0,
            'remaining_balance' => 850000
        ]);

        // Buat payment dengan status menunggu
        $payment = PaymentDetail::create([
            'payment_id' => 'PAY999991002',
            'order_product_id' => $orderProduct->order_product_id,
            'method' => 'Bank BCA',
            'amount' => 850000,
            'name' => 'admin',
            'status' => 'menunggu',
            'payment_type' => 'full',
            'order_type' => 'produk',
            'proof_photo' => 'payment_proof.jpg'
        ]);

        // Act: Konfirmasi pembayaran
        $payment->update(['status' => 'dibayar']);

        // Assert: Verifikasi status payment terupdate
        $payment->refresh();
        $this->assertEquals('dibayar', $payment->status, 'Status harus berubah menjadi dibayar');

        // Verifikasi di database
        $this->assertDatabaseHas('payment_details', [
            'payment_id' => 'PAY999991002',
            'status' => 'dibayar'
        ]);
    }

    /**
     * Test filter payment berdasarkan status
     * 
     * Skenario:
     * 1. Buat payment dengan status berbeda
     * 2. Filter payment berdasarkan status
     * 3. Verifikasi hasil filter
     */
    public function test_filter_payment_berdasarkan_status()
    {
        // Arrange: Buat customer dan order
        $customer = Customer::create([
            'customer_id' => 'CST999991003',
            'name' => 'Filter Payment Customer',
            'email' => 'filter@example.com',
            'contact' => '081234567892',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        $orderProduct = OrderProduct::create([
            'order_product_id' => 'OP999991003',
            'customer_id' => $customer->customer_id,
            'status_order' => 'menunggu',
            'status_payment' => 'belum_dibayar',
            'sub_total' => 600000,
            'discount_amount' => 0,
            'grand_total' => 650000,
            'shipping_cost' => 50000,
            'type' => 'pengiriman',
            'paid_amount' => 0,
            'remaining_balance' => 650000
        ]);

        // Buat payment dengan status menunggu
        PaymentDetail::create([
            'payment_id' => 'PAY999991003',
            'order_product_id' => $orderProduct->order_product_id,
            'method' => 'Tunai',
            'amount' => 300000,
            'name' => 'admin',
            'status' => 'menunggu',
            'payment_type' => 'down_payment',
            'order_type' => 'produk'
        ]);

        // Act: Filter payment berdasarkan status menunggu
        $menungguPayments = PaymentDetail::where('status', 'menunggu')
            ->where('payment_id', 'PAY999991003')
            ->get();

        // Assert: Verifikasi hasil filter
        $this->assertGreaterThan(0, $menungguPayments->count(), 'Harus ada payment dengan status menunggu');
        $this->assertEquals('menunggu', $menungguPayments->first()->status, 'Status payment harus menunggu');
        $this->assertEquals('PAY999991003', $menungguPayments->first()->payment_id, 'Payment ID harus sesuai');
    }
}
