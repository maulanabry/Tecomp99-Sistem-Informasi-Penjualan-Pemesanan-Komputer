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
 * Test untuk sistem pelacakan pemesanan servis customer
 * Menggunakan in-memory SQLite database untuk performa testing yang lebih cepat
 */
class CustomerOrderTrackingTest extends TestCase
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
     * Buat tabel-tabel yang diperlukan untuk testing order tracking
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

        Customer::create([
            'customer_id' => 'CST240101002',
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'contact' => '081234567891',
            'hasAccount' => true,
        ]);

        // Buat service sample
        Service::create([
            'service_id' => 1,
            'name' => 'Servis Laptop',
            'description' => 'Servis dan perbaikan laptop',
            'price' => 150000,
            'is_active' => true,
        ]);

        // Buat beberapa order dengan status berbeda untuk testing
        $orders = [
            [
                'order_service_id' => 'OSRV2401010001',
                'customer_id' => 'CST240101001',
                'status_order' => 'menunggu',
                'status_payment' => 'belum_dibayar',
                'device' => 'Laptop ASUS ROG',
                'complaints' => 'Tidak bisa menyala',
                'created_at' => now()->subDays(1),
            ],
            [
                'order_service_id' => 'OSRV2401010002',
                'customer_id' => 'CST240101001',
                'status_order' => 'diproses',
                'status_payment' => 'lunas',
                'device' => 'iPhone 13',
                'complaints' => 'Layar pecah',
                'created_at' => now()->subDays(3),
            ],
            [
                'order_service_id' => 'OSRV2401010003',
                'customer_id' => 'CST240101001',
                'status_order' => 'selesai',
                'status_payment' => 'lunas',
                'device' => 'Samsung Galaxy S22',
                'complaints' => 'Baterai boros',
                'created_at' => now()->subDays(7),
            ],
            [
                'order_service_id' => 'OSRV2401010004',
                'customer_id' => 'CST240101002',
                'status_order' => 'dibatalkan',
                'status_payment' => 'dibatalkan',
                'device' => 'MacBook Pro',
                'complaints' => 'Keyboard rusak',
                'created_at' => now()->subDays(2),
            ],
        ];

        foreach ($orders as $orderData) {
            $orderData['type'] = 'datang_langsung';
            $orderData['sub_total'] = 150000;
            $orderData['grand_total'] = 150000;

            OrderService::create($orderData);
        }
    }

    /**
     * Test customer dapat melihat daftar order servis miliknya
     */
    public function test_customer_dapat_melihat_daftar_order_servis(): void
    {
        $customerId = 'CST240101001';

        // Ambil semua order milik customer
        $orders = OrderService::where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Verifikasi customer memiliki order
        $this->assertGreaterThan(0, $orders->count());
        $this->assertEquals(3, $orders->count()); // Customer 1 memiliki 3 order

        // Verifikasi semua order milik customer yang benar
        foreach ($orders as $order) {
            $this->assertEquals($customerId, $order->customer_id);
        }

        // Verifikasi urutan berdasarkan created_at desc
        $this->assertEquals('OSRV2401010001', $orders->first()->order_service_id); // Order terbaru
        $this->assertEquals('OSRV2401010003', $orders->last()->order_service_id); // Order terlama
    }

    /**
     * Test filter order berdasarkan status
     */
    public function test_filter_order_berdasarkan_status(): void
    {
        $customerId = 'CST240101001';

        // Test filter berdasarkan status payment 'belum_dibayar'
        $unpaidOrders = OrderService::where('customer_id', $customerId)
            ->where('status_payment', 'belum_dibayar')
            ->get();

        $this->assertEquals(1, $unpaidOrders->count());
        $this->assertEquals('belum_dibayar', $unpaidOrders->first()->status_payment);

        // Test filter berdasarkan status order 'diproses'
        $processedOrders = OrderService::where('customer_id', $customerId)
            ->where('status_order', 'diproses')
            ->get();

        $this->assertEquals(1, $processedOrders->count());
        $this->assertEquals('diproses', $processedOrders->first()->status_order);

        // Test filter berdasarkan status order 'selesai'
        $completedOrders = OrderService::where('customer_id', $customerId)
            ->where('status_order', 'selesai')
            ->get();

        $this->assertEquals(1, $completedOrders->count());
        $this->assertEquals('selesai', $completedOrders->first()->status_order);

        // Test filter multiple status (diproses, sedang_dikerjakan)
        $inProgressOrders = OrderService::where('customer_id', $customerId)
            ->whereIn('status_order', ['diproses', 'sedang_dikerjakan'])
            ->get();

        $this->assertEquals(1, $inProgressOrders->count());
    }

    /**
     * Test pencarian order berdasarkan ID atau device
     */
    public function test_pencarian_order_berdasarkan_id_atau_device(): void
    {
        $customerId = 'CST240101001';

        // Test pencarian berdasarkan order ID
        $searchById = OrderService::where('customer_id', $customerId)
            ->where('order_service_id', 'like', '%OSRV2401010001%')
            ->get();

        $this->assertEquals(1, $searchById->count());
        $this->assertEquals('OSRV2401010001', $searchById->first()->order_service_id);

        // Test pencarian berdasarkan device
        $searchByDevice = OrderService::where('customer_id', $customerId)
            ->where('device', 'like', '%iPhone%')
            ->get();

        $this->assertEquals(1, $searchByDevice->count());
        $this->assertEquals('iPhone 13', $searchByDevice->first()->device);

        // Test pencarian berdasarkan complaints
        $searchByComplaints = OrderService::where('customer_id', $customerId)
            ->where('complaints', 'like', '%layar%')
            ->get();

        $this->assertEquals(1, $searchByComplaints->count());
        $this->assertEquals('Layar pecah', $searchByComplaints->first()->complaints);

        // Test pencarian dengan multiple criteria
        $searchMultiple = OrderService::where('customer_id', $customerId)
            ->where(function ($query) {
                $query->where('device', 'like', '%Samsung%')
                    ->orWhere('complaints', 'like', '%baterai%');
            })
            ->get();

        $this->assertEquals(1, $searchMultiple->count());
        $this->assertEquals('Samsung Galaxy S22', $searchMultiple->first()->device);
    }

    /**
     * Test customer dapat melihat detail order servis
     */
    public function test_customer_dapat_melihat_detail_order_servis(): void
    {
        $customerId = 'CST240101001';
        $orderId = 'OSRV2401010001';

        // Ambil detail order dengan relasi
        $order = OrderService::where('customer_id', $customerId)
            ->where('order_service_id', $orderId)
            ->with(['customer', 'items'])
            ->first();

        // Verifikasi order ditemukan
        $this->assertNotNull($order);
        $this->assertEquals($orderId, $order->order_service_id);
        $this->assertEquals($customerId, $order->customer_id);

        // Verifikasi detail order
        $this->assertEquals('Laptop ASUS ROG', $order->device);
        $this->assertEquals('Tidak bisa menyala', $order->complaints);
        $this->assertEquals('menunggu', $order->status_order);
        $this->assertEquals('belum_dibayar', $order->status_payment);

        // Verifikasi relasi customer
        $this->assertInstanceOf(Customer::class, $order->customer);
        $this->assertEquals('John Doe', $order->customer->name);

        // Test akses order milik customer lain (harus gagal)
        $otherCustomerOrder = OrderService::where('customer_id', $customerId)
            ->where('order_service_id', 'OSRV2401010004') // Order milik customer lain
            ->first();

        $this->assertNull($otherCustomerOrder);
    }

    /**
     * Test customer dapat membatalkan order jika belum dibayar
     */
    public function test_customer_dapat_membatalkan_order_belum_dibayar(): void
    {
        $customerId = 'CST240101001';
        $orderId = 'OSRV2401010001'; // Order dengan status belum_dibayar

        // Ambil order yang belum dibayar
        $order = OrderService::where('customer_id', $customerId)
            ->where('order_service_id', $orderId)
            ->first();

        // Verifikasi order dapat dibatalkan
        $this->assertEquals('belum_dibayar', $order->status_payment);
        $this->assertNotEquals('dibatalkan', $order->status_order);

        // Batalkan order
        $order->update([
            'status_order' => 'dibatalkan',
            'status_payment' => 'dibatalkan',
        ]);

        // Verifikasi order berhasil dibatalkan
        $cancelledOrder = OrderService::find($orderId);
        $this->assertEquals('dibatalkan', $cancelledOrder->status_order);
        $this->assertEquals('dibatalkan', $cancelledOrder->status_payment);

        // Test tidak bisa membatalkan order yang sudah dibayar
        $paidOrderId = 'OSRV2401010002'; // Order dengan status lunas
        $paidOrder = OrderService::find($paidOrderId);

        $this->assertEquals('lunas', $paidOrder->status_payment);

        // Simulasi validasi: order yang sudah dibayar tidak bisa dibatalkan
        $canCancel = $paidOrder->status_payment === 'belum_dibayar';
        $this->assertFalse($canCancel);

        // Test tidak bisa membatalkan order yang sudah selesai
        $completedOrderId = 'OSRV2401010003';
        $completedOrder = OrderService::find($completedOrderId);

        $this->assertEquals('selesai', $completedOrder->status_order);

        // Simulasi validasi: order yang sudah selesai tidak bisa dibatalkan
        $canCancelCompleted = $completedOrder->status_order === 'menunggu' &&
            $completedOrder->status_payment === 'belum_dibayar';
        $this->assertFalse($canCancelCompleted);
    }
}
