<?php

namespace Tests\Unit;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

/**
 * Test untuk sistem keranjang belanja customer
 * Menggunakan in-memory SQLite database untuk performa testing yang lebih cepat
 */
class CustomerCartTest extends TestCase
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
     * Buat tabel-tabel yang diperlukan untuk testing cart
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
    }

    /**
     * Test menambahkan item ke keranjang
     */
    public function test_dapat_menambahkan_item_ke_keranjang(): void
    {
        // Ambil data customer dan produk
        $customerId = 'CST240101001';
        $productId = 'PRD240101001';
        $quantity = 2;

        // Tambahkan item ke keranjang
        $cartItem = Cart::addItem($customerId, $productId, $quantity);

        // Verifikasi item berhasil ditambahkan
        $this->assertInstanceOf(Cart::class, $cartItem);
        $this->assertEquals($customerId, $cartItem->customer_id);
        $this->assertEquals($productId, $cartItem->product_id);
        $this->assertEquals($quantity, $cartItem->quantity);

        // Verifikasi item ada di database
        $this->assertDatabaseHas('carts', [
            'customer_id' => $customerId,
            'product_id' => $productId,
            'quantity' => $quantity,
        ]);
    }

    /**
     * Test update quantity item yang sudah ada di keranjang
     */
    public function test_dapat_update_quantity_item_yang_sudah_ada(): void
    {
        $customerId = 'CST240101001';
        $productId = 'PRD240101001';

        // Tambahkan item pertama kali
        Cart::addItem($customerId, $productId, 1);

        // Tambahkan lagi item yang sama
        $cartItem = Cart::addItem($customerId, $productId, 2);

        // Verifikasi quantity bertambah (1 + 2 = 3)
        $this->assertEquals(3, $cartItem->quantity);

        // Verifikasi hanya ada 1 record di database
        $cartCount = Cart::where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->count();
        $this->assertEquals(1, $cartCount);
    }

    /**
     * Test update quantity item di keranjang
     */
    public function test_dapat_update_quantity_item_keranjang(): void
    {
        $customerId = 'CST240101001';
        $productId = 'PRD240101001';

        // Tambahkan item ke keranjang
        $cartItem = Cart::addItem($customerId, $productId, 2);

        // Update quantity
        $newQuantity = 5;
        $result = $cartItem->updateQuantity($newQuantity);

        // Verifikasi update berhasil
        $this->assertTrue($result);
        $this->assertEquals($newQuantity, $cartItem->fresh()->quantity);
    }

    /**
     * Test hapus item dari keranjang dengan quantity 0
     */
    public function test_dapat_hapus_item_dengan_quantity_nol(): void
    {
        $customerId = 'CST240101001';
        $productId = 'PRD240101001';

        // Tambahkan item ke keranjang
        $cartItem = Cart::addItem($customerId, $productId, 2);
        $cartId = $cartItem->id;

        // Update quantity ke 0 (hapus item)
        $result = $cartItem->updateQuantity(0);

        // Verifikasi item terhapus
        $this->assertTrue($result);
        $this->assertDatabaseMissing('carts', ['id' => $cartId]);
    }

    /**
     * Test hitung total item di keranjang customer
     */
    public function test_dapat_hitung_total_item_customer(): void
    {
        $customerId = 'CST240101001';

        // Tambahkan beberapa item ke keranjang
        Cart::addItem($customerId, 'PRD240101001', 3);
        Cart::addItem($customerId, 'PRD240101002', 2);

        // Hitung total item
        $totalItems = Cart::getTotalItemsForCustomer($customerId);

        // Verifikasi total item (3 + 2 = 5)
        $this->assertEquals(5, $totalItems);
    }

    /**
     * Test hitung total harga keranjang customer
     */
    public function test_dapat_hitung_total_harga_customer(): void
    {
        $customerId = 'CST240101001';

        // Tambahkan item ke keranjang
        Cart::addItem($customerId, 'PRD240101001', 2); // 2 x 5,000,000 = 10,000,000
        Cart::addItem($customerId, 'PRD240101002', 1); // 1 x 12,000,000 = 12,000,000

        // Hitung total harga
        $totalPrice = Cart::getTotalPriceForCustomer($customerId);

        // Verifikasi total harga (10,000,000 + 12,000,000 = 22,000,000)
        $this->assertEquals(22000000, $totalPrice);
    }

    /**
     * Test kosongkan keranjang customer
     */
    public function test_dapat_kosongkan_keranjang_customer(): void
    {
        $customerId = 'CST240101001';

        // Tambahkan beberapa item ke keranjang
        Cart::addItem($customerId, 'PRD240101001', 2);
        Cart::addItem($customerId, 'PRD240101002', 1);

        // Verifikasi ada item di keranjang
        $this->assertEquals(2, Cart::where('customer_id', $customerId)->count());

        // Kosongkan keranjang
        $result = Cart::clearCartForCustomer($customerId);

        // Verifikasi keranjang kosong
        $this->assertTrue($result);
        $this->assertEquals(0, Cart::where('customer_id', $customerId)->count());
    }

    /**
     * Test relasi cart dengan customer
     */
    public function test_relasi_cart_dengan_customer(): void
    {
        $customerId = 'CST240101001';
        $productId = 'PRD240101001';

        // Tambahkan item ke keranjang
        $cartItem = Cart::addItem($customerId, $productId, 1);

        // Test relasi customer
        $this->assertInstanceOf(Customer::class, $cartItem->customer);
        $this->assertEquals($customerId, $cartItem->customer->customer_id);
        $this->assertEquals('John Doe', $cartItem->customer->name);
    }

    /**
     * Test relasi cart dengan product
     */
    public function test_relasi_cart_dengan_product(): void
    {
        $customerId = 'CST240101001';
        $productId = 'PRD240101001';

        // Tambahkan item ke keranjang
        $cartItem = Cart::addItem($customerId, $productId, 1);

        // Test relasi product
        $this->assertInstanceOf(Product::class, $cartItem->product);
        $this->assertEquals($productId, $cartItem->product->product_id);
        $this->assertEquals('Samsung Galaxy A54', $cartItem->product->name);
        $this->assertEquals(5000000, $cartItem->product->price);
    }

    /**
     * Test hitung total harga per item cart
     */
    public function test_dapat_hitung_total_harga_per_item(): void
    {
        $customerId = 'CST240101001';
        $productId = 'PRD240101001';
        $quantity = 3;

        // Tambahkan item ke keranjang
        $cartItem = Cart::addItem($customerId, $productId, $quantity);

        // Test total harga per item (3 x 5,000,000 = 15,000,000)
        $this->assertEquals(15000000, $cartItem->total_price);
    }

    /**
     * Test cek ketersediaan produk di cart
     */
    public function test_dapat_cek_ketersediaan_produk(): void
    {
        $customerId = 'CST240101001';
        $productId = 'PRD240101001';

        // Tambahkan item dengan quantity dalam batas stok
        $cartItem = Cart::addItem($customerId, $productId, 5);
        $this->assertTrue($cartItem->is_available);

        // Update quantity melebihi stok
        $cartItem->updateQuantity(15); // stok hanya 10
        $this->assertFalse($cartItem->fresh()->is_available);
    }
}
