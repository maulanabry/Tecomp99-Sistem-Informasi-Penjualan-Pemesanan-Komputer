<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\OrderProduct;
use App\Models\PaymentDetail;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

/**
 * Test untuk sistem payment customer
 * Menggunakan in-memory SQLite database untuk performa testing yang lebih cepat
 */
class CustomerPaymentTest extends TestCase
{
    /**
     * Setup database in-memory SQLite dan buat schema dari migrations
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Disable model observers untuk menghindari side effects
        Event::fake();

        // Konfigurasi database in-memory SQLite
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);

        // Buat schema tabel yang diperlukan untuk testing
        $this->createRequiredTables();

        // Buat data sample untuk testing
        $this->createSampleData();
    }

    /**
     * Buat tabel-tabel yang diperlukan untuk testing payment
     */
    private function createRequiredTables(): void
    {
        // Tabel customers
        Schema::create('customers', function (Blueprint $table) {
            $table->string('customer_id')->primary();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('contact');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->boolean('hasAccount')->default(false);
            $table->boolean('hasAddress')->default(false);
            $table->string('photo')->nullable();
            $table->string('gender')->nullable();
            $table->integer('service_orders_count')->default(0);
            $table->integer('product_orders_count')->default(0);
            $table->integer('total_points')->default(0);
            $table->timestamp('last_active')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel order_products
        Schema::create('order_products', function (Blueprint $table) {
            $table->string('order_product_id', 50)->primary();
            $table->string('customer_id');
            $table->enum('status_order', ['menunggu', 'diproses', 'dikirim', 'selesai', 'dibatalkan']);
            $table->enum('status_payment', ['belum_dibayar', 'down_payment', 'lunas', 'dibatalkan']);
            $table->integer('sub_total');
            $table->integer('discount_amount')->nullable();
            $table->integer('grand_total');
            $table->integer('shipping_cost')->nullable();
            $table->enum('type', ['langsung', 'pengiriman']);
            $table->text('note')->nullable();
            $table->integer('warranty_period_months')->nullable();
            $table->timestamp('warranty_expired_at')->nullable();
            $table->integer('paid_amount')->default(0);
            $table->integer('remaining_balance')->default(0);
            $table->timestamp('last_payment_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('customer_id')->on('customers');
        });

        // Tabel order_services (untuk relasi foreign key)
        Schema::create('order_services', function (Blueprint $table) {
            $table->string('order_service_id', 50)->primary();
            $table->string('customer_id');
            $table->enum('status_order', ['menunggu', 'diproses', 'sedang_dikerjakan', 'selesai', 'dibatalkan']);
            $table->enum('status_payment', ['belum_dibayar', 'down_payment', 'lunas', 'dibatalkan']);
            $table->integer('sub_total');
            $table->integer('discount_amount')->nullable();
            $table->integer('grand_total');
            $table->text('note')->nullable();
            $table->integer('warranty_period_months')->nullable();
            $table->timestamp('warranty_expired_at')->nullable();
            $table->integer('paid_amount')->default(0);
            $table->integer('remaining_balance')->default(0);
            $table->timestamp('last_payment_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('customer_id')->on('customers');
        });

        // Tabel payment_details
        Schema::create('payment_details', function (Blueprint $table) {
            $table->string('payment_id')->primary();
            $table->string('order_product_id', 50)->nullable();
            $table->string('order_service_id', 50)->nullable();
            $table->string('method', 255)->nullable();
            $table->integer('amount');
            $table->integer('change_returned')->nullable();
            $table->string('name', 255);
            $table->enum('status', ['menunggu', 'diproses', 'dibayar', 'gagal']);
            $table->enum('payment_type', ['full', 'down_payment']);
            $table->enum('order_type', ['produk', 'servis']);
            $table->string('proof_photo', 255)->nullable();
            $table->integer('cash_received')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_product_id')->references('order_product_id')->on('order_products');
            $table->foreign('order_service_id')->references('order_service_id')->on('order_services');
        });

        // Tabel system_notifications (untuk menghindari error dari observers)
        Schema::create('system_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('notifiable_id');
            $table->string('notifiable_type');
            $table->string('type');
            $table->string('subject_type')->nullable();
            $table->string('subject_id')->nullable();
            $table->text('message');
            $table->json('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Buat data sample untuk testing
     */
    private function createSampleData(): void
    {
        // Buat customer sample
        Customer::create([
            'customer_id' => 'CST240101001',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'contact' => '081234567890',
            'hasAccount' => true,
        ]);

        // Buat order sample
        OrderProduct::create([
            'order_product_id' => 'OPRD240101001',
            'customer_id' => 'CST240101001',
            'status_order' => 'menunggu',
            'status_payment' => 'belum_dibayar',
            'sub_total' => 1000000,
            'grand_total' => 1000000,
            'type' => 'langsung',
            'paid_amount' => 0,
            'remaining_balance' => 1000000,
        ]);

        // Buat order dengan down payment
        OrderProduct::create([
            'order_product_id' => 'OPRD240101002',
            'customer_id' => 'CST240101001',
            'status_order' => 'menunggu',
            'status_payment' => 'down_payment',
            'sub_total' => 2000000,
            'grand_total' => 2000000,
            'type' => 'langsung',
            'paid_amount' => 500000,
            'remaining_balance' => 1500000,
        ]);
    }

    /**
     * Test merekam pembayaran untuk order
     */
    public function test_dapat_merekam_pembayaran_order(): void
    {
        $orderId = 'OPRD240101001';
        $paymentId = $this->generatePaymentId();

        // Buat payment detail
        $payment = PaymentDetail::create([
            'payment_id' => $paymentId,
            'order_product_id' => $orderId,
            'order_service_id' => null,
            'method' => 'Bank BCA - Transfer',
            'amount' => 1000000,
            'name' => 'John Doe',
            'status' => 'menunggu',
            'payment_type' => 'full',
            'order_type' => 'produk',
            'proof_photo' => 'img-' . $paymentId . '.jpg',
        ]);

        // Verifikasi payment berhasil dibuat
        $this->assertInstanceOf(PaymentDetail::class, $payment);
        $this->assertEquals($paymentId, $payment->payment_id);
        $this->assertEquals($orderId, $payment->order_product_id);
        $this->assertEquals(1000000, $payment->amount);
        $this->assertEquals('menunggu', $payment->status);
        $this->assertEquals('full', $payment->payment_type);

        // Verifikasi data tersimpan di database
        $this->assertDatabaseHas('payment_details', [
            'payment_id' => $paymentId,
            'order_product_id' => $orderId,
            'amount' => 1000000,
            'status' => 'menunggu',
        ]);
    }

    /**
     * Test update status pembayaran dari pending ke dibayar
     */
    public function test_dapat_update_status_pembayaran(): void
    {
        $orderId = 'OPRD240101001';
        $paymentId = $this->generatePaymentId();

        // Buat payment dengan status menunggu
        $payment = PaymentDetail::create([
            'payment_id' => $paymentId,
            'order_product_id' => $orderId,
            'method' => 'Tunai',
            'amount' => 1000000,
            'name' => 'John Doe',
            'status' => 'menunggu',
            'payment_type' => 'full',
            'order_type' => 'produk',
        ]);

        // Update status ke dibayar
        $payment->update(['status' => 'dibayar']);

        // Verifikasi status berubah
        $this->assertEquals('dibayar', $payment->fresh()->status);

        // Manual update order status karena observer di-disable
        $order = OrderProduct::find($orderId);
        $order->updatePaymentStatus();

        // Verifikasi order status terupdate
        $this->assertEquals('lunas', $order->fresh()->status_payment);
        $this->assertEquals(1000000, $order->fresh()->paid_amount);
        $this->assertEquals(0, $order->fresh()->remaining_balance);
    }



    /**
     * Test validasi pembayaran (amount dan status order)
     */
    public function test_validasi_pembayaran(): void
    {
        $orderId = 'OPRD240101001';
        $order = OrderProduct::find($orderId);

        // Test validasi amount positif
        $errors = $order->validatePayment(-100000, 'full');
        $this->assertContains('Jumlah pembayaran harus lebih dari 0.', $errors);

        // Test validasi full payment amount
        $errors = $order->validatePayment(500000, 'full'); // Kurang dari grand total
        $this->assertContains('Total pembayaran tidak mencukupi untuk pelunasan penuh. Minimum: Rp ' . number_format($order->remaining_balance, 0, ',', '.'), $errors);

        // Test validasi amount yang benar
        $errors = $order->validatePayment(1000000, 'full');
        $this->assertEmpty($errors);

        // Test order yang sudah lunas
        $order->update(['status_payment' => 'lunas']);
        $this->assertFalse($order->canAcceptPayment());
    }

    /**
     * Test generate payment ID dengan format yang benar
     */
    public function test_generate_payment_id_format_benar(): void
    {
        $paymentId = $this->generatePaymentId();

        // Verifikasi format: PAYDDMMYY001
        $this->assertMatchesRegularExpression('/^PAY\d{6}\d{3}$/', $paymentId);
        $this->assertEquals(12, strlen($paymentId));

        // Verifikasi prefix
        $this->assertStringStartsWith('PAY', $paymentId);

        // Test generate multiple IDs untuk memastikan increment
        $paymentId2 = $this->generatePaymentId();
        $this->assertNotEquals($paymentId, $paymentId2);

        // Verifikasi increment
        $lastThreeDigits1 = (int) substr($paymentId, -3);
        $lastThreeDigits2 = (int) substr($paymentId2, -3);
        $this->assertEquals($lastThreeDigits1 + 1, $lastThreeDigits2);
    }

    /**
     * Test relasi payment dengan order
     */
    public function test_relasi_payment_dengan_order(): void
    {
        $orderId = 'OPRD240101001';
        $paymentId = $this->generatePaymentId();

        $payment = PaymentDetail::create([
            'payment_id' => $paymentId,
            'order_product_id' => $orderId,
            'method' => 'Tunai',
            'amount' => 1000000,
            'name' => 'John Doe',
            'status' => 'dibayar',
            'payment_type' => 'full',
            'order_type' => 'produk',
        ]);

        // Test relasi payment ke order
        $this->assertInstanceOf(OrderProduct::class, $payment->orderProduct);
        $this->assertEquals($orderId, $payment->orderProduct->order_product_id);

        // Test relasi order ke payments
        $order = OrderProduct::find($orderId);
        $this->assertTrue($order->payments->contains($payment));
        $this->assertEquals(1, $order->payments->count());

        // Test accessor order
        $this->assertInstanceOf(OrderProduct::class, $payment->order);
        $this->assertEquals($orderId, $payment->order->order_product_id);
    }

    /**
     * Helper method untuk generate payment ID
     */
    private function generatePaymentId(): string
    {
        static $counter = 0;
        $counter++;

        $date = now()->format('dmy');
        $prefix = 'PAY' . $date;

        return $prefix . str_pad($counter, 3, '0', STR_PAD_LEFT);
    }
}
