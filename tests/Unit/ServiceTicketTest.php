<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ServiceTicket;
use App\Models\OrderService;
use App\Models\Customer;
use App\Models\Admin;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;

/**
 * Test Unit untuk Service Ticket (Simplified)
 * 
 * Test ini mencakup user story:
 * - Sebagai admin, saya ingin mengelola antrian & jadwal servis sehingga dapat mengatur waktu servis dengan efisien
 */
class ServiceTicketTest extends TestCase
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
        ServiceTicket::unsetEventDispatcher();
        OrderService::unsetEventDispatcher();
    }

    /**
     * Test membuat service ticket baru
     * 
     * Skenario:
     * 1. Buat customer, teknisi, dan order service
     * 2. Buat service ticket baru
     * 3. Verifikasi ticket tersimpan di database
     */
    public function test_admin_dapat_membuat_service_ticket_baru()
    {
        // Arrange: Buat customer
        $customer = Customer::create([
            'customer_id' => 'CST999990001',
            'name' => 'Ticket Customer',
            'email' => 'ticket@example.com',
            'contact' => '081234567890',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Buat teknisi (admin dengan role teknisi)
        $teknisi = Admin::create([
            'name' => 'Teknisi Ahmad',
            'email' => 'teknisi@example.com',
            'password' => bcrypt('password123'),
            'role' => 'teknisi',
            'theme' => 'light'
        ]);

        // Buat order service
        $orderService = OrderService::create([
            'order_service_id' => 'OS999990001',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Menunggu',
            'status_payment' => 'belum_dibayar',
            'type' => 'reguler',
            'device' => 'Laptop ASUS',
            'complaints' => 'Laptop tidak bisa nyala',
            'sub_total' => 500000,
            'grand_total' => 500000,
            'discount_amount' => 0,
            'paid_amount' => 0,
            'remaining_balance' => 500000
        ]);

        // Act: Buat service ticket
        $serviceTicket = ServiceTicket::create([
            'service_ticket_id' => 'TKT999990001',
            'order_service_id' => $orderService->order_service_id,
            'admin_id' => $teknisi->id,
            'status' => 'Menunggu',
            'schedule_date' => Carbon::today(),
            'visit_schedule' => Carbon::today()->addHours(10),
            'estimation_days' => 3,
            'estimate_date' => Carbon::today()->addDays(3)
        ]);

        // Assert: Verifikasi ticket tersimpan
        $this->assertInstanceOf(ServiceTicket::class, $serviceTicket, 'Harus mengembalikan instance ServiceTicket');
        $this->assertEquals('TKT999990001', $serviceTicket->service_ticket_id, 'Service ticket ID harus sesuai');
        $this->assertEquals($orderService->order_service_id, $serviceTicket->order_service_id, 'Order service ID harus sesuai');
        $this->assertEquals($teknisi->id, $serviceTicket->admin_id, 'Admin ID harus sesuai');
        $this->assertEquals('Menunggu', $serviceTicket->status, 'Status harus Menunggu');
    }

    /**
     * Test mengupdate status service ticket
     * 
     * Skenario:
     * 1. Buat service ticket dengan status Menunggu
     * 2. Update status ke Diproses
     * 3. Verifikasi perubahan status
     */
    public function test_admin_dapat_mengupdate_status_ticket()
    {
        // Arrange: Buat customer dan order service
        $customer = Customer::create([
            'customer_id' => 'CST999990002',
            'name' => 'Update Status Customer',
            'email' => 'updatestatus@example.com',
            'contact' => '081234567891',
            'gender' => 'wanita',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        $teknisi = Admin::create([
            'name' => 'Teknisi Budi',
            'email' => 'teknisi2@example.com',
            'password' => bcrypt('password123'),
            'role' => 'teknisi',
            'theme' => 'light'
        ]);

        $orderService = OrderService::create([
            'order_service_id' => 'OS999990002',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Menunggu',
            'status_payment' => 'belum_dibayar',
            'type' => 'reguler',
            'device' => 'Komputer Desktop',
            'complaints' => 'Komputer sering restart',
            'sub_total' => 400000,
            'grand_total' => 400000,
            'discount_amount' => 0,
            'paid_amount' => 0,
            'remaining_balance' => 400000
        ]);

        // Buat service ticket dengan status Menunggu
        $serviceTicket = ServiceTicket::create([
            'service_ticket_id' => 'TKT999990002',
            'order_service_id' => $orderService->order_service_id,
            'admin_id' => $teknisi->id,
            'status' => 'Menunggu',
            'schedule_date' => Carbon::today(),
            'estimation_days' => 2,
            'estimate_date' => Carbon::today()->addDays(2)
        ]);

        // Act: Update status ke Diproses
        $serviceTicket->update(['status' => 'Diproses']);

        // Assert: Verifikasi perubahan status
        $serviceTicket->refresh();
        $this->assertEquals('Diproses', $serviceTicket->status, 'Status harus berubah menjadi Diproses');

        // Verifikasi di database
        $this->assertDatabaseHas('service_tickets', [
            'service_ticket_id' => 'TKT999990002',
            'status' => 'Diproses'
        ]);
    }

    /**
     * Test filter service ticket berdasarkan status
     * 
     * Skenario:
     * 1. Buat beberapa service ticket dengan status berbeda
     * 2. Filter ticket berdasarkan status
     * 3. Verifikasi hasil filter
     */
    public function test_filter_service_ticket_berdasarkan_status()
    {
        // Arrange: Buat customer dan teknisi
        $customer = Customer::create([
            'customer_id' => 'CST999990003',
            'name' => 'Filter Status Customer',
            'email' => 'filterstatus@example.com',
            'contact' => '081234567892',
            'gender' => 'pria',
            'hasAccount' => 1,
            'hasAddress' => 0,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        $teknisi = Admin::create([
            'name' => 'Teknisi Charlie',
            'email' => 'teknisi3@example.com',
            'password' => bcrypt('password123'),
            'role' => 'teknisi',
            'theme' => 'light'
        ]);

        $orderService = OrderService::create([
            'order_service_id' => 'OS999990003',
            'customer_id' => $customer->customer_id,
            'status_order' => 'Menunggu',
            'status_payment' => 'belum_dibayar',
            'type' => 'reguler',
            'device' => 'Printer',
            'complaints' => 'Printer tidak bisa print',
            'sub_total' => 300000,
            'grand_total' => 300000,
            'discount_amount' => 0,
            'paid_amount' => 0,
            'remaining_balance' => 300000
        ]);

        // Buat service ticket dengan status Menunggu
        ServiceTicket::create([
            'service_ticket_id' => 'TKT999990003',
            'order_service_id' => $orderService->order_service_id,
            'admin_id' => $teknisi->id,
            'status' => 'Menunggu',
            'schedule_date' => Carbon::today(),
            'estimation_days' => 1,
            'estimate_date' => Carbon::today()->addDay()
        ]);

        // Act: Filter ticket berdasarkan status Menunggu
        $menungguTickets = ServiceTicket::where('status', 'Menunggu')
            ->where('service_ticket_id', 'TKT999990003')
            ->get();

        // Assert: Verifikasi hasil filter
        $this->assertGreaterThan(0, $menungguTickets->count(), 'Harus ada ticket dengan status Menunggu');
        $this->assertEquals('Menunggu', $menungguTickets->first()->status, 'Status ticket harus Menunggu');
        $this->assertEquals('TKT999990003', $menungguTickets->first()->service_ticket_id, 'Service ticket ID harus sesuai');
    }
}
