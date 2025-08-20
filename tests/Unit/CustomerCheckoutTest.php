<?php

namespace Tests\Unit;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\OrderProduct;
use App\Models\OrderProductItem;
use App\Models\CustomerAddress;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

/**
 * Test untuk sistem checkout customer
 * Menggunakan in-memory SQLite database untuk performa testing yang lebih cepat
 */
class CustomerCheckoutTest extends TestCase
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
     * Buat tabel-tabel yang diperlukan untuk testing checkout
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

        // Tabel brands
        Schema::create('brands', function (Blueprint $table) {
            $table->id('brand_id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id('categories_id');
            $table->string('name');
            $table->string('type')->default('product');
            $table->string('slug');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabel products
        Schema::create('products', function (Blueprint $table) {
            $table->string('product_id')->primary();
            $table->unsignedBigInteger('categories_id');
            $table->unsignedBigInteger('brand_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('price');
            $table->integer('weight')->default(0);
            $table->integer('stock');
            $table->boolean('is_active')->default(true);
            $table->integer('sold_count')->default(0);
            $table->string('slug');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('categories_id')->references('categories_id')->on('categories');
            $table->foreign('brand_id')->references('brand_id')->on('brands');
        });

        // Tabel carts
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id');
            $table->string('product_id');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('product_id')->references('product_id')->on('products');
        });

        // Tabel customer_addresses
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id', 20);
            $table->unsignedInteger('province_id')->nullable();
            $table->string('province_name')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->string('city_name')->nullable();
            $table->unsignedInteger('district_id')->nullable();
            $table->string('district_name')->nullable();
            $table->unsignedInteger('subdistrict_id')->nullable();
            $table->string('subdistrict_name')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->text('detail_address')->nullable();
            $table->boolean('is_default')->default(true);
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('customers');
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

        // Tabel order_product_items
        Schema::create('order_product_items', function (Blueprint $table) {
            $table->id();
            $table->string('order_product_id', 50);
            $table->string('product_id');
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('total');
            $table->timestamps();

            $table->foreign('order_product_id')->references('order_product_id')->on('order_products');
            $table->foreign('product_id')->references('product_id')->on('products');
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
            'hasAddress' => true,
        ]);

        // Buat alamat customer
        CustomerAddress::create([
            'customer_id' => 'CST240101001',
            'province_id' => 1,
            'province_name' => 'DKI Jakarta',
            'city_id' => 1,
            'city_name' => 'Jakarta',
            'district_id' => 1,
            'district_name' => 'Jakarta Pusat',
            'subdistrict_id' => 1,
            'subdistrict_name' => 'Menteng',
            'postal_code' => '12345',
            'detail_address' => 'Jl. Merdeka No. 123',
            'is_default' => true,
        ]);

        // Buat brand sample
        Brand::create([
            'brand_id' => 1,
            'name' => 'Samsung',
        ]);

        // Buat category sample
        Category::create([
            'categories_id' => 1,
            'name' => 'Smartphone',
            'type' => 'product',
            'slug' => 'smartphone',
        ]);

        // Buat produk sample
        Product::create([
            'product_id' => 'PRD240101001',
            'categories_id' => 1,
            'brand_id' => 1,
            'name' => 'Samsung Galaxy A54',
            'description' => 'Smartphone terbaru dari Samsung',
            'price' => 5000000,
            'stock' => 10,
            'is_active' => true,
            'slug' => 'samsung-galaxy-a54',
        ]);

        Product::create([
            'product_id' => 'PRD240101002',
            'categories_id' => 1,
            'brand_id' => 1,
            'name' => 'Samsung Galaxy S24',
            'description' => 'Flagship Samsung terbaru',
            'price' => 12000000,
            'stock' => 5,
            'is_active' => true,
            'slug' => 'samsung-galaxy-s24',
        ]);

        // Buat cart items untuk testing
        Cart::create([
            'customer_id' => 'CST240101001',
            'product_id' => 'PRD240101001',
            'quantity' => 2,
        ]);

        Cart::create([
            'customer_id' => 'CST240101001',
            'product_id' => 'PRD240101002',
            'quantity' => 1,
        ]);
    }

    /**
     * Test membuat order dari cart items
     */
    public function test_dapat_membuat_order_dari_cart(): void
    {
        $customerId = 'CST240101001';
        $cartItems = Cart::where('customer_id', $customerId)->with('product')->get();

        // Hitung total
        $subTotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        $shippingCost = 15000;
        $grandTotal = $subTotal + $shippingCost;

        // Generate order ID
        $orderId = $this->generateOrderId();

        // Buat order
        $order = OrderProduct::create([
            'order_product_id' => $orderId,
            'customer_id' => $customerId,
            'status_order' => 'menunggu',
            'status_payment' => 'belum_dibayar',
            'sub_total' => $subTotal,
            'shipping_cost' => $shippingCost,
            'grand_total' => $grandTotal,
            'type' => 'pengiriman',
            'remaining_balance' => $grandTotal,
        ]);

        // Buat order items
        foreach ($cartItems as $cartItem) {
            OrderProductItem::create([
                'order_product_id' => $orderId,
                'product_id' => $cartItem->product_id,
                'quantity' => $cartItem->quantity,
                'price' => $cartItem->product->price,
                'total' => $cartItem->product->price * $cartItem->quantity,
            ]);
        }

        // Verifikasi order berhasil dibuat
        $this->assertInstanceOf(OrderProduct::class, $order);
        $this->assertEquals($customerId, $order->customer_id);
        $this->assertEquals($grandTotal, $order->grand_total);
        $this->assertEquals('menunggu', $order->status_order);
        $this->assertEquals('belum_dibayar', $order->status_payment);

        // Verifikasi order items
        $this->assertEquals(2, $order->items()->count());
        $this->assertDatabaseHas('order_product_items', [
            'order_product_id' => $orderId,
            'product_id' => 'PRD240101001',
            'quantity' => 2,
        ]);
    }

    /**
     * Test menghitung total order dengan benar
     */
    public function test_dapat_menghitung_total_order(): void
    {
        $customerId = 'CST240101001';
        $cartItems = Cart::where('customer_id', $customerId)->with('product')->get();

        // Hitung subtotal dari cart items
        $subTotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Hitung dengan shipping cost
        $shippingCost = 25000;
        $discount = 100000;
        $grandTotal = $subTotal + $shippingCost - $discount;

        // Verifikasi perhitungan
        $expectedSubTotal = (5000000 * 2) + (12000000 * 1); // 22,000,000
        $expectedGrandTotal = $expectedSubTotal + $shippingCost - $discount; // 21,925,000

        $this->assertEquals($expectedSubTotal, $subTotal);
        $this->assertEquals($expectedGrandTotal, $grandTotal);
    }

    /**
     * Test inisialisasi status order yang benar
     */
    public function test_inisialisasi_status_order_benar(): void
    {
        $orderId = $this->generateOrderId();
        $customerId = 'CST240101001';

        $order = OrderProduct::create([
            'order_product_id' => $orderId,
            'customer_id' => $customerId,
            'status_order' => 'menunggu',
            'status_payment' => 'belum_dibayar',
            'sub_total' => 1000000,
            'grand_total' => 1000000,
            'type' => 'langsung',
        ]);

        // Verifikasi status awal
        $this->assertEquals('menunggu', $order->status_order);
        $this->assertEquals('belum_dibayar', $order->status_payment);
        $this->assertEquals(0, $order->paid_amount);
        $this->assertEquals(1000000, $order->remaining_balance);
        $this->assertTrue($order->canAcceptPayment());
    }

    /**
     * Test validasi checkout dengan cart kosong
     */
    public function test_validasi_checkout_cart_kosong(): void
    {
        $customerId = 'CST240101002'; // Customer tanpa cart items

        // Buat customer baru tanpa cart
        Customer::create([
            'customer_id' => $customerId,
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'contact' => '081234567891',
            'hasAccount' => true,
        ]);

        $cartItems = Cart::where('customer_id', $customerId)->get();

        // Verifikasi cart kosong
        $this->assertTrue($cartItems->isEmpty());
        $this->assertEquals(0, $cartItems->count());

        // Simulasi validasi checkout
        $canCheckout = $cartItems->isNotEmpty() && $cartItems->every(function ($item) {
            return $item->is_available;
        });

        $this->assertFalse($canCheckout);
    }

    /**
     * Test generate order ID dengan format yang benar
     */
    public function test_generate_order_id_format_benar(): void
    {
        $orderId = $this->generateOrderId();

        // Verifikasi format: OPRDDDMMYY001
        $this->assertMatchesRegularExpression('/^OPRD\d{6}\d{3}$/', $orderId);
        $this->assertEquals(12, strlen($orderId));

        // Verifikasi prefix
        $this->assertStringStartsWith('OPRD', $orderId);

        // Test generate multiple IDs untuk memastikan increment
        $orderId2 = $this->generateOrderId();
        $this->assertNotEquals($orderId, $orderId2);
    }

    /**
     * Test relasi order dengan customer
     */
    public function test_relasi_order_dengan_customer(): void
    {
        $customerId = 'CST240101001';
        $orderId = $this->generateOrderId();

        $order = OrderProduct::create([
            'order_product_id' => $orderId,
            'customer_id' => $customerId,
            'status_order' => 'menunggu',
            'status_payment' => 'belum_dibayar',
            'sub_total' => 1000000,
            'grand_total' => 1000000,
            'type' => 'langsung',
        ]);

        // Test relasi customer
        $this->assertInstanceOf(Customer::class, $order->customer);
        $this->assertEquals($customerId, $order->customer->customer_id);
        $this->assertEquals('John Doe', $order->customer->name);

        // Test relasi dari customer ke orders
        $customer = Customer::find($customerId);
        $this->assertTrue($customer->orderProducts->contains($order));
    }

    /**
     * Helper method untuk generate order ID
     */
    private function generateOrderId(): string
    {
        static $counter = 0;
        $counter++;

        $date = now()->format('dmy');
        $prefix = 'OPRD' . $date;

        return $prefix . str_pad($counter, 3, '0', STR_PAD_LEFT);
    }
}
