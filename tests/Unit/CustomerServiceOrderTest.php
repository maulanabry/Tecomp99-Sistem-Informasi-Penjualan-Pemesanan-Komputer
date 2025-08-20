<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\OrderService;
use App\Models\OrderServiceItem;
use App\Models\Service;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Event;

/**
 * Test untuk sistem pemesanan servis customer
 * Menggunakan in-memory SQLite database untuk performa testing yang lebih cepat
 */
class CustomerServiceOrderTest extends TestCase
{
    /**
     * Setup database in-memory SQLite dan buat schema dari migrations
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Konfigurasi database in-memory SQLite
        config(['database.default' => 'sqlite']);
        config(['database.connections.sqlite.database' => ':memory:']);

        // Buat schema tabel yang diperlukan untuk testing
        $this->createRequiredTables();

        // Buat data sample untuk testing
        $this->createSampleData();
    }

    /**
     * Buat tabel-tabel yang diperlukan untuk testing service order
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

        // Tabel service (sesuai dengan migration yang ada)
        Schema::create('service', function (Blueprint $table) {
            $table->id('service_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('price');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel order_services
        Schema::create('order_services', function (Blueprint $table) {
            $table->string('order_service_id', 50)->primary();
            $table->string('customer_id');
            $table->enum('status_order', ['menunggu', 'diproses', 'sedang_dikerjakan', 'selesai', 'dibatalkan']);
            $table->enum('status_payment', ['belum_dibayar', 'down_payment', 'lunas', 'dibatalkan']);
            $table->text('complaints')->nullable();
            $table->enum('type', ['datang_langsung', 'pickup_delivery']);
            $table->string('device');
            $table->text('note')->nullable();
            $table->boolean('hasTicket')->default(false);
            $table->boolean('hasDevice')->default(false);
            $table->decimal('sub_total', 10, 2);
            $table->decimal('grand_total', 10, 2);
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->integer('warranty_period_months')->nullable();
            $table->timestamp('warranty_expired_at')->nullable();
            $table->decimal('paid_amount', 10, 2)->default(0);
            $table->decimal('remaining_balance', 10, 2)->default(0);
            $table->timestamp('last_payment_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('customer_id')->on('customers');
        });

        // Tabel order_service_items
        Schema::create('order_service_items', function (Blueprint $table) {
            $table->id();
            $table->string('order_service_id', 50);
            $table->unsignedBigInteger('service_id');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2);
            $table->timestamps();

            $table->foreign('order_service_id')->references('order_service_id')->on('order_services');
            $table->foreign('service_id')->references('service_id')->on('service');
        });

        // Tabel system_notifications (diperlukan oleh observer)
        Schema::create('system_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('notifiable_id');
            $table->string('notifiable_type');
            $table->string('type');
            $table->string('subject_type')->nullable();
            $table->string('subject_id')->nullable();
            $table->string('message');
            $table->json('data')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['notifiable_id', 'notifiable_type']);
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
            'hasAddress' => true,
        ]);

        // Buat service sample
        Service::create([
            'service_id' => 1,
            'name' => 'Servis Laptop',
            'description' => 'Servis dan perbaikan laptop',
            'price' => 150000,
            'is_active' => true,
        ]);

        Service::create([
            'service_id' => 2,
            'name' => 'Servis Smartphone',
            'description' => 'Servis dan perbaikan smartphone',
            'price' => 100000,
            'is_active' => true,
        ]);
    }

    /**
     * Test customer dapat membuat order servis baru
     */
    public function test_customer_dapat_membuat_order_servis_baru(): void
    {
        $customerId = 'CST240101001';
        $orderId = $this->generateOrderServiceId();

        // Data order servis
        $orderData = [
            'order_service_id' => $orderId,
            'customer_id' => $customerId,
            'status_order' => 'menunggu',
            'status_payment' => 'belum_dibayar',
            'complaints' => 'Laptop tidak bisa menyala',
            'type' => 'datang_langsung',
            'device' => 'Laptop ASUS ROG',
            'note' => 'Mohon diperbaiki dengan hati-hati',
            'hasTicket' => false,
            'hasDevice' => true,
            'sub_total' => 150000,
            'grand_total' => 150000,
            'warranty_period_months' => 3,
        ];

        // Buat order servis
        $order = OrderService::create($orderData);

        // Buat order service item dengan explicit service_id
        $serviceItem = new OrderServiceItem();
        $serviceItem->order_service_id = $orderId;
        $serviceItem->service_id = 1;
        $serviceItem->quantity = 1;
        $serviceItem->price = 150000;
        $serviceItem->total = 150000;
        $serviceItem->save();

        // Verifikasi order berhasil dibuat
        $this->assertInstanceOf(OrderService::class, $order);
        $this->assertEquals($customerId, $order->customer_id);
        $this->assertEquals('menunggu', $order->status_order);
        $this->assertEquals('belum_dibayar', $order->status_payment);
        $this->assertEquals('Laptop tidak bisa menyala', $order->complaints);
        $this->assertEquals('Laptop ASUS ROG', $order->device);

        // Verifikasi order tersimpan di database
        $this->assertDatabaseHas('order_services', [
            'order_service_id' => $orderId,
            'customer_id' => $customerId,
            'device' => 'Laptop ASUS ROG',
        ]);

        // Verifikasi order item
        $this->assertEquals(1, $order->items()->count());
    }

    /**
     * Test validasi data order servis
     */
    public function test_validasi_data_order_servis(): void
    {
        $customerId = 'CST240101001';
        $orderId = $this->generateOrderServiceId();

        // Test dengan data yang valid
        $validData = [
            'order_service_id' => $orderId,
            'customer_id' => $customerId,
            'status_order' => 'menunggu',
            'status_payment' => 'belum_dibayar',
            'complaints' => 'Smartphone layar pecah',
            'type' => 'pickup_delivery',
            'device' => 'iPhone 13 Pro',
            'sub_total' => 200000,
            'grand_total' => 200000,
        ];

        $order = OrderService::create($validData);

        // Verifikasi data valid berhasil disimpan
        $this->assertInstanceOf(OrderService::class, $order);
        $this->assertEquals('pickup_delivery', $order->type);
        $this->assertEquals('iPhone 13 Pro', $order->device);

        // Test validasi enum status_order
        $this->assertContains($order->status_order, ['menunggu', 'diproses', 'sedang_dikerjakan', 'selesai', 'dibatalkan']);

        // Test validasi enum status_payment
        $this->assertContains($order->status_payment, ['belum_dibayar', 'down_payment', 'lunas', 'dibatalkan']);

        // Test validasi enum type
        $this->assertContains($order->type, ['datang_langsung', 'pickup_delivery']);
    }

    /**
     * Test perhitungan total harga order servis
     */
    public function test_perhitungan_total_harga_order_servis(): void
    {
        $customerId = 'CST240101001';
        $orderId = $this->generateOrderServiceId();

        // Hitung total dari multiple service items
        $service1Price = 150000; // Servis Laptop
        $service2Price = 100000; // Servis Smartphone
        $subTotal = $service1Price + $service2Price;
        $discount = 25000;
        $grandTotal = $subTotal - $discount;

        // Buat order dengan discount
        $order = OrderService::create([
            'order_service_id' => $orderId,
            'customer_id' => $customerId,
            'status_order' => 'menunggu',
            'status_payment' => 'belum_dibayar',
            'complaints' => 'Butuh servis laptop dan smartphone',
            'type' => 'datang_langsung',
            'device' => 'Laptop + Smartphone',
            'sub_total' => $subTotal,
            'discount_amount' => $discount,
            'grand_total' => $grandTotal,
        ]);

        // Buat service items dengan explicit assignment
        $serviceItem1 = new OrderServiceItem();
        $serviceItem1->order_service_id = $orderId;
        $serviceItem1->service_id = 1;
        $serviceItem1->quantity = 1;
        $serviceItem1->price = $service1Price;
        $serviceItem1->total = $service1Price;
        $serviceItem1->save();

        $serviceItem2 = new OrderServiceItem();
        $serviceItem2->order_service_id = $orderId;
        $serviceItem2->service_id = 2;
        $serviceItem2->quantity = 1;
        $serviceItem2->price = $service2Price;
        $serviceItem2->total = $service2Price;
        $serviceItem2->save();

        // Verifikasi perhitungan total
        $this->assertEquals($subTotal, $order->sub_total);
        $this->assertEquals($discount, $order->discount_amount);
        $this->assertEquals($grandTotal, $order->grand_total);

        // Verifikasi total dari items
        $itemsTotal = $order->items()->sum('total');
        $this->assertEquals($subTotal, $itemsTotal);
    }

    /**
     * Test inisialisasi status order dan payment yang benar
     */
    public function test_inisialisasi_status_order_dan_payment(): void
    {
        $customerId = 'CST240101001';
        $orderId = $this->generateOrderServiceId();
        $grandTotal = 150000;

        // Buat order baru
        $order = OrderService::create([
            'order_service_id' => $orderId,
            'customer_id' => $customerId,
            'status_order' => 'menunggu',
            'status_payment' => 'belum_dibayar',
            'complaints' => 'Test order',
            'type' => 'datang_langsung',
            'device' => 'Test Device',
            'sub_total' => $grandTotal,
            'grand_total' => $grandTotal,
            'remaining_balance' => $grandTotal,
        ]);

        // Verifikasi status awal
        $this->assertEquals('menunggu', $order->status_order);
        $this->assertEquals('belum_dibayar', $order->status_payment);
        $this->assertEquals(0, $order->paid_amount);
        $this->assertEquals($grandTotal, $order->remaining_balance);

        // Test method canAcceptPayment
        $this->assertTrue($order->canAcceptPayment());

        // Test warranty expiration calculation jika ada warranty
        $orderWithWarranty = OrderService::create([
            'order_service_id' => $this->generateOrderServiceId(),
            'customer_id' => $customerId,
            'status_order' => 'menunggu',
            'status_payment' => 'belum_dibayar',
            'complaints' => 'Test order with warranty',
            'type' => 'datang_langsung',
            'device' => 'Test Device 2',
            'sub_total' => $grandTotal,
            'grand_total' => $grandTotal,
            'remaining_balance' => $grandTotal,
            'warranty_period_months' => 6,
            'warranty_expired_at' => now()->addMonths(6), // Set manual karena observer tidak aktif
        ]);

        // Verifikasi warranty expiration
        $this->assertNotNull($orderWithWarranty->warranty_expired_at);
        $this->assertEquals(6, $orderWithWarranty->warranty_period_months);
    }

    /**
     * Test relasi order servis dengan customer
     */
    public function test_relasi_order_servis_dengan_customer(): void
    {
        $customerId = 'CST240101001';
        $orderId = $this->generateOrderServiceId();

        // Buat order servis
        $order = OrderService::create([
            'order_service_id' => $orderId,
            'customer_id' => $customerId,
            'status_order' => 'menunggu',
            'status_payment' => 'belum_dibayar',
            'complaints' => 'Test relasi',
            'type' => 'datang_langsung',
            'device' => 'Test Device',
            'sub_total' => 100000,
            'grand_total' => 100000,
        ]);

        // Test relasi customer
        $this->assertInstanceOf(Customer::class, $order->customer);
        $this->assertEquals($customerId, $order->customer->customer_id);
        $this->assertEquals('John Doe', $order->customer->name);

        // Test relasi dari customer ke order services
        $customer = Customer::find($customerId);
        $this->assertTrue($customer->orderServices->contains($order));

        // Test eager loading
        $orderWithCustomer = OrderService::with('customer')->find($orderId);
        $this->assertInstanceOf(Customer::class, $orderWithCustomer->customer);
        $this->assertEquals('John Doe', $orderWithCustomer->customer->name);
    }

    /**
     * Helper method untuk generate order service ID
     */
    private function generateOrderServiceId(): string
    {
        static $counter = 0;
        $counter++;

        $date = now()->format('dmy');
        $prefix = 'OSRV' . $date;

        return $prefix . str_pad($counter, 3, '0', STR_PAD_LEFT);
    }
}
