<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\OrderService;
use App\Models\ServiceTicket;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Category;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Test Unit untuk Laporan Servis
 * 
 * Test ini mencakup user story Owner:
 * - Sebagai owner, saya ingin melihat laporan performa servis
 */
class ServiceReportTest extends TestCase
{

    /**
     * Test menghitung jumlah servis yang selesai
     * 
     * Menguji logika perhitungan jumlah servis selesai
     */
    public function test_owner_dapat_melihat_jumlah_servis_selesai()
    {
        // Arrange: Buat customer
        $customer = Customer::create([
            'customer_id' => 'CST001',
            'name' => 'Customer Service Test',
            'email' => 'customer.service@test.com',
            'contact' => '081234567890',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Servis selesai
        OrderService::create([
            'order_service_id' => 'OS001',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Selesai',
            'status_payment' => 'lunas',
            'complaints' => 'Laptop tidak bisa nyala',
            'type' => 'reguler',
            'device' => 'Laptop ASUS',
            'hasTicket' => true,
            'hasDevice' => true,
            'sub_total' => 500000,
            'grand_total' => 500000,
            'discount_amount' => 0,
            'paid_amount' => 500000,
            'remaining_balance' => 0
        ]);

        OrderService::create([
            'order_service_id' => 'OS002',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Selesai',
            'status_payment' => 'lunas',
            'complaints' => 'Laptop lemot',
            'type' => 'reguler',
            'device' => 'Laptop HP',
            'hasTicket' => true,
            'hasDevice' => true,
            'sub_total' => 300000,
            'grand_total' => 300000,
            'discount_amount' => 0,
            'paid_amount' => 300000,
            'remaining_balance' => 0
        ]);

        // Servis dalam proses (tidak dihitung)
        OrderService::create([
            'order_service_id' => 'OS003',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Diproses',
            'status_payment' => 'belum_dibayar',
            'complaints' => 'Laptop rusak',
            'type' => 'reguler',
            'device' => 'Laptop Dell',
            'hasTicket' => true,
            'hasDevice' => true,
            'sub_total' => 400000,
            'grand_total' => 400000,
            'discount_amount' => 0,
            'paid_amount' => 0,
            'remaining_balance' => 400000
        ]);

        // Act: Hitung jumlah servis yang selesai
        $jumlahServisSelesai = OrderService::where('status_order', 'Selesai')->count();

        // Assert: Verifikasi jumlah servis selesai
        $this->assertEquals(2, $jumlahServisSelesai);
    }

    /**
     * Test menghitung total revenue dari layanan servis
     * 
     * Menguji logika perhitungan total revenue servis
     */
    public function test_owner_dapat_melihat_total_revenue_servis()
    {
        // Arrange: Buat customer
        $customer = Customer::create([
            'customer_id' => 'CST002',
            'name' => 'Customer Service Test 2',
            'email' => 'customer.service2@test.com',
            'contact' => '081234567891',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Buat order servis yang selesai dan lunas
        OrderService::create([
            'order_service_id' => 'OS004',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Selesai',
            'status_payment' => 'lunas',
            'complaints' => 'Laptop tidak bisa nyala',
            'type' => 'reguler',
            'device' => 'Laptop ASUS',
            'hasTicket' => true,
            'hasDevice' => true,
            'sub_total' => 500000,
            'grand_total' => 500000,
            'discount_amount' => 0,
            'paid_amount' => 500000,
            'remaining_balance' => 0
        ]);

        OrderService::create([
            'order_service_id' => 'OS005',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Selesai',
            'status_payment' => 'lunas',
            'complaints' => 'Laptop lemot',
            'type' => 'reguler',
            'device' => 'Laptop HP',
            'hasTicket' => true,
            'hasDevice' => true,
            'sub_total' => 400000,
            'grand_total' => 350000, // Ada diskon
            'discount_amount' => 50000,
            'paid_amount' => 350000,
            'remaining_balance' => 0
        ]);

        // Act: Hitung total revenue dari servis yang selesai dan lunas
        $totalRevenue = OrderService::where('status_order', 'Selesai')
            ->where('status_payment', 'lunas')
            ->sum('grand_total');

        // Assert: Verifikasi total revenue benar
        $this->assertEquals(850000, $totalRevenue);
    }

    /**
     * Test laporan performa teknisi
     * 
     * Menguji logika perhitungan performa teknisi
     */
    public function test_owner_dapat_melihat_performa_teknisi()
    {
        // Arrange: Buat teknisi
        $teknisi1 = Admin::create([
            'name' => 'Teknisi 1',
            'email' => 'teknisi1@tecomp99.com',
            'password' => bcrypt('password123'),
            'role' => 'teknisi',
            'theme' => 'light'
        ]);

        $teknisi2 = Admin::create([
            'name' => 'Teknisi 2',
            'email' => 'teknisi2@tecomp99.com',
            'password' => bcrypt('password123'),
            'role' => 'teknisi',
            'theme' => 'light'
        ]);

        $customer = Customer::create([
            'customer_id' => 'CST003',
            'name' => 'Customer Service Test 3',
            'email' => 'customer.service3@test.com',
            'contact' => '081234567892',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Buat order servis
        $orderService1 = OrderService::create([
            'order_service_id' => 'OS006',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Selesai',
            'status_payment' => 'lunas',
            'complaints' => 'Laptop tidak bisa nyala',
            'type' => 'reguler',
            'device' => 'Laptop ASUS',
            'hasTicket' => true,
            'hasDevice' => true,
            'sub_total' => 500000,
            'grand_total' => 500000,
            'discount_amount' => 0,
            'paid_amount' => 500000,
            'remaining_balance' => 0
        ]);

        $orderService2 = OrderService::create([
            'order_service_id' => 'OS007',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Selesai',
            'status_payment' => 'lunas',
            'complaints' => 'Laptop lemot',
            'type' => 'reguler',
            'device' => 'Laptop HP',
            'hasTicket' => true,
            'hasDevice' => true,
            'sub_total' => 300000,
            'grand_total' => 300000,
            'discount_amount' => 0,
            'paid_amount' => 300000,
            'remaining_balance' => 0
        ]);

        // Buat service ticket dengan teknisi berbeda
        ServiceTicket::create([
            'service_ticket_id' => 'TKT001',
            'ticket_id' => 'TKT001',
            'order_service_id' => $orderService1->order_service_id,
            'admin_id' => $teknisi1->id,
            'status' => 'selesai',
            'priority' => 'normal',
            'description' => 'Perbaikan laptop',
            'solution' => 'Ganti RAM',
            'schedule_date' => now()
        ]);

        ServiceTicket::create([
            'service_ticket_id' => 'TKT002',
            'ticket_id' => 'TKT002',
            'order_service_id' => $orderService2->order_service_id,
            'admin_id' => $teknisi2->id,
            'status' => 'selesai',
            'priority' => 'normal',
            'description' => 'Maintenance laptop',
            'solution' => 'Bersihkan dan install ulang',
            'schedule_date' => now()
        ]);

        // Act: Hitung performa teknisi
        $performaTeknisi = ServiceTicket::select('admin_id')
            ->selectRaw('COUNT(*) as jumlah_ticket')
            ->where('status', 'selesai')
            ->groupBy('admin_id')
            ->get();

        // Assert: Verifikasi performa teknisi
        $this->assertCount(2, $performaTeknisi);
        $this->assertEquals(1, $performaTeknisi->first()->jumlah_ticket);
    }

    /**
     * Test laporan berdasarkan tipe servis
     * 
     * Menguji logika filtering berdasarkan tipe servis
     */
    public function test_owner_dapat_melihat_laporan_berdasarkan_tipe_servis()
    {
        // Arrange: Buat customer
        $customer = Customer::create([
            'customer_id' => 'CST004',
            'name' => 'Customer Service Test 4',
            'email' => 'customer.service4@test.com',
            'contact' => '081234567893',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Servis reguler
        OrderService::create([
            'order_service_id' => 'OS008',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Selesai',
            'status_payment' => 'lunas',
            'complaints' => 'Laptop tidak bisa nyala',
            'type' => 'reguler',
            'device' => 'Laptop ASUS',
            'hasTicket' => true,
            'hasDevice' => true,
            'sub_total' => 500000,
            'grand_total' => 500000,
            'discount_amount' => 0,
            'paid_amount' => 500000,
            'remaining_balance' => 0
        ]);

        // Servis onsite
        OrderService::create([
            'order_service_id' => 'OS009',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Selesai',
            'status_payment' => 'lunas',
            'complaints' => 'Laptop lemot',
            'type' => 'onsite',
            'device' => 'Laptop Dell',
            'hasTicket' => true,
            'hasDevice' => true,
            'sub_total' => 300000,
            'grand_total' => 300000,
            'discount_amount' => 0,
            'paid_amount' => 300000,
            'remaining_balance' => 0
        ]);

        // Act: Hitung berdasarkan tipe servis
        $jumlahReguler = OrderService::where('type', 'reguler')
            ->where('status_order', 'Selesai')
            ->count();

        $jumlahOnsite = OrderService::where('type', 'onsite')
            ->where('status_order', 'Selesai')
            ->count();

        // Assert: Verifikasi perhitungan berdasarkan tipe
        $this->assertEquals(1, $jumlahReguler);
        $this->assertEquals(1, $jumlahOnsite);
    }

    /**
     * Test laporan berdasarkan status pembayaran servis
     * 
     * Menguji logika filtering berdasarkan status pembayaran
     */
    public function test_owner_dapat_melihat_laporan_status_pembayaran_servis()
    {
        // Arrange: Buat customer
        $customer = Customer::create([
            'customer_id' => 'CST005',
            'name' => 'Customer Service Test 5',
            'email' => 'customer.service5@test.com',
            'contact' => '081234567894',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Servis lunas
        OrderService::create([
            'order_service_id' => 'OS010',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Selesai',
            'status_payment' => 'lunas',
            'complaints' => 'Laptop tidak bisa nyala',
            'type' => 'reguler',
            'device' => 'Laptop ASUS',
            'hasTicket' => true,
            'hasDevice' => true,
            'sub_total' => 500000,
            'grand_total' => 500000,
            'discount_amount' => 0,
            'paid_amount' => 500000,
            'remaining_balance' => 0
        ]);

        // Servis down payment
        OrderService::create([
            'order_service_id' => 'OS011',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Diproses',
            'status_payment' => 'down_payment',
            'complaints' => 'Laptop lemot',
            'type' => 'reguler',
            'device' => 'Laptop HP',
            'hasTicket' => true,
            'hasDevice' => true,
            'sub_total' => 400000,
            'grand_total' => 400000,
            'discount_amount' => 0,
            'paid_amount' => 200000,
            'remaining_balance' => 200000
        ]);

        // Act: Hitung berdasarkan status pembayaran
        $jumlahLunas = OrderService::where('status_payment', 'lunas')->count();
        $jumlahDownPayment = OrderService::where('status_payment', 'down_payment')->count();

        // Assert: Verifikasi perhitungan berdasarkan status pembayaran
        $this->assertEquals(1, $jumlahLunas);
        $this->assertEquals(1, $jumlahDownPayment);
    }
}
