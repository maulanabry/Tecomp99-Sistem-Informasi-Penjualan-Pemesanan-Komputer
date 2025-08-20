<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\OrderProduct;
use App\Models\OrderProductItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test Unit untuk Laporan Produk
 * 
 * Test ini mencakup user story Owner:
 * - Sebagai owner, saya ingin melihat laporan penjualan produk
 */
class ProductReportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test menghitung total revenue dari penjualan produk
     * 
     * Menguji logika perhitungan total revenue
     */
    public function test_owner_dapat_melihat_total_revenue_penjualan_produk()
    {
        // Arrange: Buat customer
        $customer = Customer::create([
            'customer_id' => 'CST001',
            'name' => 'Customer Test',
            'email' => 'customer@test.com',
            'contact' => '081234567890',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Buat order produk yang selesai dan lunas
        OrderProduct::create([
            'order_product_id' => 'OP001',
            'customer_id' => $customer->customer_id,
            'status_order' => 'selesai',
            'status_payment' => 'lunas',
            'sub_total' => 15000000,
            'discount_amount' => 0,
            'grand_total' => 15000000,
            'type' => 'langsung',
            'paid_amount' => 15000000,
            'remaining_balance' => 0
        ]);

        OrderProduct::create([
            'order_product_id' => 'OP002',
            'customer_id' => $customer->customer_id,
            'status_order' => 'selesai',
            'status_payment' => 'lunas',
            'sub_total' => 12000000,
            'discount_amount' => 1000000,
            'grand_total' => 11000000,
            'type' => 'langsung',
            'paid_amount' => 11000000,
            'remaining_balance' => 0
        ]);

        // Act: Hitung total revenue
        $totalRevenue = OrderProduct::where('status_order', 'selesai')
            ->where('status_payment', 'lunas')
            ->sum('grand_total');

        // Assert: Verifikasi total revenue benar
        $this->assertEquals(26000000, $totalRevenue);
    }

    /**
     * Test menghitung jumlah produk terjual
     * 
     * Menguji logika perhitungan quantity produk terjual
     */
    public function test_owner_dapat_melihat_jumlah_produk_terjual()
    {
        // Arrange: Buat data yang diperlukan
        $customer = Customer::create([
            'customer_id' => 'CST002',
            'name' => 'Customer Test 2',
            'email' => 'customer2@test.com',
            'contact' => '081234567891',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        $category = Category::create([
            'name' => 'Laptop',
            'type' => 'produk',
            'slug' => 'laptop'
        ]);

        $brand = Brand::create([
            'name' => 'ASUS',
            'slug' => 'asus'
        ]);

        $product = Product::create([
            'product_id' => 'PRD001',
            'categories_id' => $category->categories_id,
            'brand_id' => $brand->brand_id,
            'name' => 'Laptop ASUS ROG',
            'description' => 'Gaming Laptop',
            'price' => 15000000,
            'stock' => 10,
            'weight' => 2500,
            'slug' => 'laptop-asus-rog',
            'is_active' => true,
            'sold_count' => 0
        ]);

        $order = OrderProduct::create([
            'order_product_id' => 'OP003',
            'customer_id' => $customer->customer_id,
            'status_order' => 'selesai',
            'status_payment' => 'lunas',
            'sub_total' => 30000000,
            'discount_amount' => 0,
            'grand_total' => 30000000,
            'type' => 'langsung',
            'paid_amount' => 30000000,
            'remaining_balance' => 0
        ]);

        OrderProductItem::create([
            'order_product_id' => $order->order_product_id,
            'product_id' => $product->product_id,
            'quantity' => 2,
            'price' => 15000000,
            'total' => 30000000,
            'item_total' => 30000000
        ]);

        // Act: Hitung total quantity terjual
        $totalTerjual = OrderProductItem::whereHas('orderProduct', function ($query) {
            $query->where('status_order', 'selesai');
        })->sum('quantity');

        // Assert: Verifikasi jumlah terjual benar
        $this->assertEquals(2, $totalTerjual);
    }

    /**
     * Test laporan berdasarkan status pembayaran
     * 
     * Menguji logika filtering berdasarkan status pembayaran
     */
    public function test_owner_dapat_melihat_laporan_berdasarkan_status_pembayaran()
    {
        // Arrange: Buat customer
        $customer = Customer::create([
            'customer_id' => 'CST003',
            'name' => 'Customer Test 3',
            'email' => 'customer3@test.com',
            'contact' => '081234567892',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Order lunas
        OrderProduct::create([
            'order_product_id' => 'OP004',
            'customer_id' => $customer->customer_id,
            'status_order' => 'selesai',
            'status_payment' => 'lunas',
            'sub_total' => 15000000,
            'discount_amount' => 0,
            'grand_total' => 15000000,
            'type' => 'langsung',
            'paid_amount' => 15000000,
            'remaining_balance' => 0
        ]);

        // Order down payment
        OrderProduct::create([
            'order_product_id' => 'OP005',
            'customer_id' => $customer->customer_id,
            'status_order' => 'diproses',
            'status_payment' => 'down_payment',
            'sub_total' => 20000000,
            'discount_amount' => 0,
            'grand_total' => 20000000,
            'type' => 'langsung',
            'paid_amount' => 10000000,
            'remaining_balance' => 10000000
        ]);

        // Act: Hitung berdasarkan status pembayaran
        $jumlahOrderLunas = OrderProduct::where('status_payment', 'lunas')->count();
        $jumlahOrderDownPayment = OrderProduct::where('status_payment', 'down_payment')->count();

        // Assert: Verifikasi perhitungan berdasarkan status
        $this->assertEquals(1, $jumlahOrderLunas);
        $this->assertEquals(1, $jumlahOrderDownPayment);
    }

    /**
     * Test laporan berdasarkan tipe order
     * 
     * Menguji logika filtering berdasarkan tipe order
     */
    public function test_owner_dapat_melihat_laporan_berdasarkan_tipe_order()
    {
        // Arrange: Buat customer
        $customer = Customer::create([
            'customer_id' => 'CST004',
            'name' => 'Customer Test 4',
            'email' => 'customer4@test.com',
            'contact' => '081234567893',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Order langsung
        OrderProduct::create([
            'order_product_id' => 'OP006',
            'customer_id' => $customer->customer_id,
            'status_order' => 'selesai',
            'status_payment' => 'lunas',
            'sub_total' => 15000000,
            'discount_amount' => 0,
            'grand_total' => 15000000,
            'shipping_cost' => 0,
            'type' => 'langsung',
            'paid_amount' => 15000000,
            'remaining_balance' => 0
        ]);

        // Order pengiriman
        OrderProduct::create([
            'order_product_id' => 'OP007',
            'customer_id' => $customer->customer_id,
            'status_order' => 'selesai',
            'status_payment' => 'lunas',
            'sub_total' => 20000000,
            'discount_amount' => 0,
            'grand_total' => 20050000,
            'shipping_cost' => 50000,
            'type' => 'pengiriman',
            'paid_amount' => 20050000,
            'remaining_balance' => 0
        ]);

        // Act: Hitung berdasarkan tipe order
        $jumlahOrderLangsung = OrderProduct::where('type', 'langsung')->count();
        $jumlahOrderPengiriman = OrderProduct::where('type', 'pengiriman')->count();

        // Assert: Verifikasi perhitungan berdasarkan tipe
        $this->assertEquals(1, $jumlahOrderLangsung);
        $this->assertEquals(1, $jumlahOrderPengiriman);
    }
}
