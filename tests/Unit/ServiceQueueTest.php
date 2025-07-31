<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ServiceTicket;
use App\Models\OrderService;
use App\Models\Customer;
use App\Models\Admin;
use App\Models\ServiceAction;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Carbon\Carbon;

/**
 * Test Unit untuk Manajemen Antrian & Jadwal Servis
 * 
 * Test ini mencakup user story:
 * - Sebagai admin, saya ingin mengelola antrian & jadwal servis sehingga dapat mengatur waktu servis dengan efisien
 */
class ServiceQueueTest extends TestCase
{
    use DatabaseTransactions;

    private $customer;
    private $teknisi;
    private $orderService;
    private $serviceTicketData;

    /**
     * Setup yang dijalankan sebelum setiap test
     * Menyiapkan data untuk testing service queue management
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Buat customer untuk testing
        $this->customer = Customer::create([
            'customer_id' => 'CST240101001',
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'contact' => '081234567890',
            'gender' => 'Laki-laki',
            'hasAccount' => 1,
            'hasAddress' => 1,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Buat teknisi (admin dengan role teknisi) untuk testing
        $this->teknisi = Admin::create([
            'name' => 'Teknisi Ahmad',
            'email' => 'teknisi.ahmad@example.com',
            'password' => bcrypt('password123'),
            'role' => 'teknisi',
            'theme' => 'light',
            'last_seen_at' => Carbon::now()
        ]);

        // Buat order service untuk testing
        $this->orderService = OrderService::create([
            'order_service_id' => 'ORD-SRV-001',
            'customer_id' => $this->customer->customer_id,
            'status_order' => 'pending',
            'status_payment' => 'belum_dibayar',
            'complaints' => 'Laptop tidak bisa nyala',
            'type' => 'reguler',
            'device' => 'Laptop ASUS ROG',
            'note' => 'Perlu perbaikan motherboard',
            'hasTicket' => false,
            'hasDevice' => false,
            'sub_total' => 500000,
            'grand_total' => 500000,
            'discount_amount' => 0,
            'paid_amount' => 0,
            'remaining_balance' => 500000
        ]);

        // Data service ticket untuk testing
        $this->serviceTicketData = [
            'service_ticket_id' => 'TKT-001',
            'order_service_id' => $this->orderService->order_service_id,
            'admin_id' => $this->teknisi->id,
            'status' => 'Menunggu',
            'schedule_date' => Carbon::today(),
            'visit_schedule' => Carbon::today()->addHours(10), // 10:00 AM
            'estimation_days' => 3,
            'estimate_date' => Carbon::today()->addDays(3)
        ];
    }

    /**
     * Test membuat service ticket baru
     * 
     * Skenario:
     * 1. Buat service ticket baru dengan data valid
     * 2. Verifikasi ticket tersimpan di database
     * 3. Verifikasi relasi dengan order service dan teknisi
     */
    public function test_admin_dapat_membuat_service_ticket_baru()
    {
        // Act: Buat service ticket baru
        $serviceTicket = ServiceTicket::create($this->serviceTicketData);

        // Assert: Verifikasi ticket tersimpan
        $this->assertInstanceOf(ServiceTicket::class, $serviceTicket, 'Harus mengembalikan instance ServiceTicket');
        $this->assertDatabaseHas('service_tickets', [
            'service_ticket_id' => 'TKT-001',
            'order_service_id' => $this->orderService->order_service_id,
            'admin_id' => $this->teknisi->id,
            'status' => 'Menunggu'
        ]);

        // Verifikasi relasi dengan order service
        $this->assertEquals($this->orderService->order_service_id, $serviceTicket->order_service_id, 'Order service ID harus sesuai');
        $this->assertEquals('John Doe', $serviceTicket->orderService->customer->name, 'Nama customer harus sesuai');

        // Verifikasi relasi dengan teknisi
        $this->assertEquals($this->teknisi->id, $serviceTicket->admin_id, 'Admin ID harus sesuai');
        $this->assertEquals('Teknisi Ahmad', $serviceTicket->admin->name, 'Nama teknisi harus sesuai');
        $this->assertEquals('teknisi', $serviceTicket->admin->role, 'Role harus teknisi');
    }

    /**
     * Test assign teknisi ke service ticket
     * 
     * Skenario:
     * 1. Buat service ticket tanpa teknisi
     * 2. Assign teknisi ke ticket
     * 3. Verifikasi assignment berhasil
     */
    public function test_admin_dapat_assign_teknisi_ke_ticket()
    {
        // Arrange: Buat service ticket tanpa teknisi
        $ticketData = $this->serviceTicketData;
        $ticketData['admin_id'] = null;
        $serviceTicket = ServiceTicket::create($ticketData);

        $this->assertNull($serviceTicket->admin_id, 'Admin ID awal harus null');

        // Act: Assign teknisi ke ticket
        $serviceTicket->update(['admin_id' => $this->teknisi->id]);

        // Assert: Verifikasi assignment
        $serviceTicket->refresh();
        $this->assertEquals($this->teknisi->id, $serviceTicket->admin_id, 'Admin ID harus ter-assign');
        $this->assertEquals('Teknisi Ahmad', $serviceTicket->admin->name, 'Nama teknisi harus sesuai');

        // Verifikasi di database
        $this->assertDatabaseHas('service_tickets', [
            'service_ticket_id' => 'TKT-001',
            'admin_id' => $this->teknisi->id
        ]);
    }

    /**
     * Test mengatur jadwal kunjungan service
     * 
     * Skenario:
     * 1. Buat service ticket
     * 2. Atur jadwal kunjungan
     * 3. Verifikasi jadwal tersimpan dengan benar
     */
    public function test_admin_dapat_mengatur_jadwal_kunjungan()
    {
        // Arrange: Buat service ticket
        $serviceTicket = ServiceTicket::create($this->serviceTicketData);

        // Act: Update jadwal kunjungan
        $newVisitSchedule = Carbon::tomorrow()->setTime(14, 30); // Besok jam 14:30
        $serviceTicket->update(['visit_schedule' => $newVisitSchedule]);

        // Assert: Verifikasi jadwal terupdate
        $serviceTicket->refresh();
        $this->assertEquals($newVisitSchedule->format('Y-m-d H:i:s'), $serviceTicket->visit_schedule->format('Y-m-d H:i:s'), 'Jadwal kunjungan harus terupdate');

        // Verifikasi di database
        $this->assertDatabaseHas('service_tickets', [
            'service_ticket_id' => 'TKT-001',
            'visit_schedule' => $newVisitSchedule->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Test mengatur estimasi penyelesaian
     * 
     * Skenario:
     * 1. Buat service ticket dengan estimasi
     * 2. Update estimasi hari dan tanggal
     * 3. Verifikasi estimasi tersimpan
     */
    public function test_admin_dapat_mengatur_estimasi_penyelesaian()
    {
        // Arrange: Buat service ticket
        $serviceTicket = ServiceTicket::create($this->serviceTicketData);

        // Act: Update estimasi
        $newEstimationDays = 5;
        $newEstimateDate = Carbon::today()->addDays($newEstimationDays);

        $serviceTicket->update([
            'estimation_days' => $newEstimationDays,
            'estimate_date' => $newEstimateDate
        ]);

        // Assert: Verifikasi estimasi terupdate
        $serviceTicket->refresh();
        $this->assertEquals($newEstimationDays, $serviceTicket->estimation_days, 'Estimation days harus terupdate');
        $this->assertEquals($newEstimateDate->format('Y-m-d'), $serviceTicket->estimate_date->format('Y-m-d'), 'Estimate date harus terupdate');

        // Verifikasi di database
        $this->assertDatabaseHas('service_tickets', [
            'service_ticket_id' => 'TKT-001',
            'estimation_days' => $newEstimationDays,
            'estimate_date' => $newEstimateDate->format('Y-m-d')
        ]);
    }

    /**
     * Test mengupdate status service ticket
     * 
     * Skenario:
     * 1. Buat service ticket dengan status Menunggu
     * 2. Update status melalui berbagai tahapan
     * 3. Verifikasi perubahan status
     */
    public function test_admin_dapat_mengupdate_status_ticket()
    {
        // Arrange: Buat service ticket
        $serviceTicket = ServiceTicket::create($this->serviceTicketData);
        $this->assertEquals('Menunggu', $serviceTicket->status, 'Status awal harus Menunggu');

        // Act & Assert: Update status ke Diproses
        $serviceTicket->update(['status' => 'Diproses']);
        $serviceTicket->refresh();
        $this->assertEquals('Diproses', $serviceTicket->status, 'Status harus berubah menjadi Diproses');

        // Act & Assert: Update status ke Diantar
        $serviceTicket->update(['status' => 'Diantar']);
        $serviceTicket->refresh();
        $this->assertEquals('Diantar', $serviceTicket->status, 'Status harus berubah menjadi Diantar');

        // Act & Assert: Update status ke Selesai
        $serviceTicket->update(['status' => 'Selesai']);
        $serviceTicket->refresh();
        $this->assertEquals('Selesai', $serviceTicket->status, 'Status harus berubah menjadi Selesai');

        // Verifikasi di database
        $this->assertDatabaseHas('service_tickets', [
            'service_ticket_id' => 'TKT-001',
            'status' => 'Selesai'
        ]);
    }

    /**
     * Test menambahkan action ke service ticket
     * 
     * Skenario:
     * 1. Buat service ticket
     * 2. Tambahkan service action
     * 3. Verifikasi relasi ticket-action
     */
    public function test_admin_dapat_menambahkan_action_ke_ticket()
    {
        // Arrange: Buat service ticket
        $serviceTicket = ServiceTicket::create($this->serviceTicketData);

        // Act: Tambahkan service action
        $serviceAction = ServiceAction::create([
            'service_ticket_id' => $serviceTicket->service_ticket_id,
            'action_type' => 'diagnosis',
            'description' => 'Melakukan diagnosis awal pada motherboard',
            'notes' => 'Ditemukan kerusakan pada kapasitor',
            'created_by' => $this->teknisi->id,
            'action_date' => Carbon::now()
        ]);

        // Assert: Verifikasi action tersimpan dan relasi
        $this->assertDatabaseHas('service_actions', [
            'service_ticket_id' => 'TKT-001',
            'action_type' => 'diagnosis',
            'description' => 'Melakukan diagnosis awal pada motherboard'
        ]);

        // Verifikasi relasi ticket-action
        $ticketActions = $serviceTicket->actions;
        $this->assertCount(1, $ticketActions, 'Ticket harus memiliki 1 action');
        $this->assertEquals('diagnosis', $ticketActions->first()->action_type, 'Action type harus sesuai');
    }

    /**
     * Test antrian service berdasarkan FIFO (First In First Out)
     * 
     * Skenario:
     * 1. Buat beberapa service ticket pada waktu berbeda
     * 2. Query antrian berdasarkan created_at
     * 3. Verifikasi urutan FIFO
     */
    public function test_antrian_service_berdasarkan_fifo()
    {
        // Arrange: Buat beberapa service ticket dengan waktu berbeda
        $ticket1 = ServiceTicket::create($this->serviceTicketData);

        // Simulasi ticket kedua dibuat 1 jam kemudian
        $ticket2Data = $this->serviceTicketData;
        $ticket2Data['service_ticket_id'] = 'TKT-002';
        $ticket2 = ServiceTicket::create($ticket2Data);
        $ticket2->update(['created_at' => Carbon::now()->addHour()]);

        // Simulasi ticket ketiga dibuat 2 jam kemudian
        $ticket3Data = $this->serviceTicketData;
        $ticket3Data['service_ticket_id'] = 'TKT-003';
        $ticket3 = ServiceTicket::create($ticket3Data);
        $ticket3->update(['created_at' => Carbon::now()->addHours(2)]);

        // Act: Query antrian berdasarkan FIFO
        $queueTickets = ServiceTicket::where('status', 'Menunggu')
            ->orderBy('created_at', 'asc')
            ->get();

        // Assert: Verifikasi urutan FIFO
        $this->assertCount(3, $queueTickets, 'Harus ada 3 ticket dalam antrian');
        $this->assertEquals('TKT-001', $queueTickets[0]->service_ticket_id, 'Ticket pertama harus TKT-001');
        $this->assertEquals('TKT-002', $queueTickets[1]->service_ticket_id, 'Ticket kedua harus TKT-002');
        $this->assertEquals('TKT-003', $queueTickets[2]->service_ticket_id, 'Ticket ketiga harus TKT-003');
    }

    /**
     * Test filter antrian berdasarkan teknisi
     * 
     * Skenario:
     * 1. Buat teknisi kedua
     * 2. Buat ticket untuk teknisi berbeda
     * 3. Filter antrian berdasarkan teknisi
     */
    public function test_filter_antrian_berdasarkan_teknisi()
    {
        // Arrange: Buat teknisi kedua
        $teknisi2 = Admin::create([
            'name' => 'Teknisi Budi',
            'email' => 'teknisi.budi@example.com',
            'password' => bcrypt('password123'),
            'role' => 'teknisi',
            'theme' => 'light',
            'last_seen_at' => Carbon::now()
        ]);

        // Buat ticket untuk teknisi pertama
        ServiceTicket::create($this->serviceTicketData);

        // Buat ticket untuk teknisi kedua
        $ticket2Data = $this->serviceTicketData;
        $ticket2Data['service_ticket_id'] = 'TKT-002';
        $ticket2Data['admin_id'] = $teknisi2->id;
        ServiceTicket::create($ticket2Data);

        // Act: Filter antrian berdasarkan teknisi
        $teknisi1Tickets = ServiceTicket::where('admin_id', $this->teknisi->id)->get();
        $teknisi2Tickets = ServiceTicket::where('admin_id', $teknisi2->id)->get();

        // Assert: Verifikasi hasil filter
        $this->assertCount(1, $teknisi1Tickets, 'Teknisi 1 harus memiliki 1 ticket');
        $this->assertCount(1, $teknisi2Tickets, 'Teknisi 2 harus memiliki 1 ticket');

        $this->assertEquals('TKT-001', $teknisi1Tickets->first()->service_ticket_id, 'Ticket teknisi 1 harus TKT-001');
        $this->assertEquals('TKT-002', $teknisi2Tickets->first()->service_ticket_id, 'Ticket teknisi 2 harus TKT-002');
    }

    /**
     * Test filter ticket berdasarkan jadwal kunjungan hari ini
     * 
     * Skenario:
     * 1. Buat ticket dengan jadwal kunjungan berbeda
     * 2. Filter ticket berdasarkan jadwal hari ini
     * 3. Verifikasi hasil filter
     */
    public function test_filter_ticket_jadwal_kunjungan_hari_ini()
    {
        // Arrange: Buat ticket dengan jadwal hari ini
        ServiceTicket::create($this->serviceTicketData);

        // Buat ticket dengan jadwal besok
        $tomorrowTicketData = $this->serviceTicketData;
        $tomorrowTicketData['service_ticket_id'] = 'TKT-002';
        $tomorrowTicketData['visit_schedule'] = Carbon::tomorrow()->setTime(10, 0);
        ServiceTicket::create($tomorrowTicketData);

        // Buat ticket tanpa jadwal kunjungan
        $noScheduleTicketData = $this->serviceTicketData;
        $noScheduleTicketData['service_ticket_id'] = 'TKT-003';
        $noScheduleTicketData['visit_schedule'] = null;
        ServiceTicket::create($noScheduleTicketData);

        // Act: Filter ticket dengan jadwal kunjungan hari ini
        $todayVisits = ServiceTicket::whereDate('visit_schedule', Carbon::today())->get();

        // Assert: Verifikasi hasil filter
        $this->assertCount(1, $todayVisits, 'Harus ada 1 ticket dengan jadwal hari ini');
        $this->assertEquals('TKT-001', $todayVisits->first()->service_ticket_id, 'Ticket hari ini harus TKT-001');
    }

    /**
     * Test identifikasi ticket overdue
     * 
     * Skenario:
     * 1. Buat ticket dengan estimate_date yang sudah lewat
     * 2. Filter ticket overdue
     * 3. Verifikasi hasil filter
     */
    public function test_identifikasi_ticket_overdue()
    {
        // Arrange: Buat ticket dengan estimate_date kemarin (overdue)
        $overdueTicketData = $this->serviceTicketData;
        $overdueTicketData['estimate_date'] = Carbon::yesterday();
        $overdueTicketData['status'] = 'Diproses'; // Belum selesai
        $overdueTicket = ServiceTicket::create($overdueTicketData);

        // Buat ticket dengan estimate_date besok (tidak overdue)
        $normalTicketData = $this->serviceTicketData;
        $normalTicketData['service_ticket_id'] = 'TKT-002';
        $normalTicketData['estimate_date'] = Carbon::tomorrow();
        $normalTicketData['status'] = 'Diproses';
        ServiceTicket::create($normalTicketData);

        // Buat ticket yang sudah selesai (tidak overdue meskipun lewat estimate)
        $completedTicketData = $this->serviceTicketData;
        $completedTicketData['service_ticket_id'] = 'TKT-003';
        $completedTicketData['estimate_date'] = Carbon::yesterday();
        $completedTicketData['status'] = 'Selesai';
        ServiceTicket::create($completedTicketData);

        // Act: Filter ticket overdue
        $overdueTickets = ServiceTicket::where('estimate_date', '<', Carbon::today())
            ->whereNotIn('status', ['Selesai', 'Dibatalkan'])
            ->get();

        // Assert: Verifikasi hasil filter
        $this->assertCount(1, $overdueTickets, 'Harus ada 1 ticket overdue');
        $this->assertEquals('TKT-001', $overdueTickets->first()->service_ticket_id, 'Ticket overdue harus TKT-001');
    }

    /**
     * Test filter ticket berdasarkan tipe service
     * 
     * Skenario:
     * 1. Buat order service dengan tipe berbeda
     * 2. Buat ticket untuk order tersebut
     * 3. Filter ticket berdasarkan tipe service
     */
    public function test_filter_ticket_berdasarkan_tipe_service()
    {
        // Arrange: Buat order service reguler
        ServiceTicket::create($this->serviceTicketData); // Tipe reguler

        // Buat order service express
        $expressOrderService = OrderService::create([
            'order_service_id' => 'ORD-SRV-002',
            'customer_id' => $this->customer->customer_id,
            'status_order' => 'pending',
            'status_payment' => 'belum_dibayar',
            'complaints' => 'Laptop lambat',
            'type' => 'express',
            'device' => 'Laptop Dell',
            'sub_total' => 750000,
            'grand_total' => 750000,
            'discount_amount' => 0,
            'paid_amount' => 0,
            'remaining_balance' => 750000
        ]);

        $expressTicketData = $this->serviceTicketData;
        $expressTicketData['service_ticket_id'] = 'TKT-002';
        $expressTicketData['order_service_id'] = $expressOrderService->order_service_id;
        ServiceTicket::create($expressTicketData);

        // Act: Filter ticket berdasarkan tipe service
        $regulerTickets = ServiceTicket::whereHas('orderService', function ($query) {
            $query->where('type', 'reguler');
        })->get();

        $expressTickets = ServiceTicket::whereHas('orderService', function ($query) {
            $query->where('type', 'express');
        })->get();

        // Assert: Verifikasi hasil filter
        $this->assertCount(1, $regulerTickets, 'Harus ada 1 ticket reguler');
        $this->assertCount(1, $expressTickets, 'Harus ada 1 ticket express');

        $this->assertEquals('TKT-001', $regulerTickets->first()->service_ticket_id, 'Ticket reguler harus TKT-001');
        $this->assertEquals('TKT-002', $expressTickets->first()->service_ticket_id, 'Ticket express harus TKT-002');
    }

    /**
     * Test soft delete service ticket
     * 
     * Skenario:
     * 1. Buat service ticket
     * 2. Hapus ticket (soft delete)
     * 3. Verifikasi ticket tidak muncul di query normal
     * 4. Verifikasi ticket masih ada dengan withTrashed()
     */
    public function test_admin_dapat_menghapus_ticket_soft_delete()
    {
        // Arrange: Buat service ticket
        $serviceTicket = ServiceTicket::create($this->serviceTicketData);
        $ticketId = $serviceTicket->service_ticket_id;

        // Act: Hapus ticket (soft delete)
        $serviceTicket->delete();

        // Assert: Verifikasi ticket tidak muncul di query normal
        $this->assertNull(ServiceTicket::find($ticketId), 'Ticket tidak boleh ditemukan setelah dihapus');

        // Verifikasi ticket masih ada dengan withTrashed()
        $trashedTicket = ServiceTicket::withTrashed()->find($ticketId);
        $this->assertNotNull($trashedTicket, 'Ticket harus masih ada dengan withTrashed()');
        $this->assertNotNull($trashedTicket->deleted_at, 'deleted_at harus ter-set');
    }

    /**
     * Test statistik antrian service
     * 
     * Skenario:
     * 1. Buat ticket dengan status berbeda
     * 2. Hitung statistik antrian
     * 3. Verifikasi perhitungan statistik
     */
    public function test_statistik_antrian_service()
    {
        // Arrange: Buat ticket dengan status berbeda
        ServiceTicket::create($this->serviceTicketData); // Status Menunggu

        $ticket2Data = $this->serviceTicketData;
        $ticket2Data['service_ticket_id'] = 'TKT-002';
        $ticket2Data['status'] = 'Diproses';
        ServiceTicket::create($ticket2Data);

        $ticket3Data = $this->serviceTicketData;
        $ticket3Data['service_ticket_id'] = 'TKT-003';
        $ticket3Data['status'] = 'Selesai';
        ServiceTicket::create($ticket3Data);

        $ticket4Data = $this->serviceTicketData;
        $ticket4Data['service_ticket_id'] = 'TKT-004';
        $ticket4Data['status'] = 'Dibatalkan';
        ServiceTicket::create($ticket4Data);

        // Act: Hitung statistik
        $waitingCount = ServiceTicket::where('status', 'Menunggu')->count();
        $processingCount = ServiceTicket::where('status', 'Diproses')->count();
        $completedCount = ServiceTicket::where('status', 'Selesai')->count();
        $cancelledCount = ServiceTicket::where('status', 'Dibatalkan')->count();
        $totalCount = ServiceTicket::count();

        // Assert: Verifikasi statistik
        $this->assertEquals(1, $waitingCount, 'Harus ada 1 ticket menunggu');
        $this->assertEquals(1, $processingCount, 'Harus ada 1 ticket diproses');
        $this->assertEquals(1, $completedCount, 'Harus ada 1 ticket selesai');
        $this->assertEquals(1, $cancelledCount, 'Harus ada 1 ticket dibatalkan');
        $this->assertEquals(4, $totalCount, 'Total harus ada 4 ticket');
    }

    /**
     * Test pencarian ticket berdasarkan customer
     * 
     * Skenario:
     * 1. Buat customer kedua dan ticket-nya
     * 2. Cari ticket berdasarkan nama customer
     * 3. Verifikasi hasil pencarian
     */
    public function test_pencarian_ticket_berdasarkan_customer()
    {
        // Arrange: Buat customer kedua
        $customer2 = Customer::create([
            'customer_id' => 'CST240101002',
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'contact' => '081234567891',
            'gender' => 'Perempuan',
            'hasAccount' => 1,
            'hasAddress' => 1,
            'service_orders_count' => 0,
            'product_orders_count' => 0,
            'total_points' => 0
        ]);

        // Buat order service untuk customer kedua
        $orderService2 = OrderService::create([
            'order_service_id' => 'ORD-SRV-002',
            'customer_id' => $customer2->customer_id,
            'status_order' => 'pending',
            'status_payment' => 'belum_dibayar',
            'complaints' => 'Laptop lambat',
            'type' => 'reguler',
            'device' => 'Laptop Dell',
            'sub_total' => 400000,
            'grand_total' => 400000,
            'discount_amount' => 0,
            'paid_amount' => 0,
            'remaining_balance' => 400000
        ]);

        // Buat ticket untuk customer pertama dan kedua
        ServiceTicket::create($this->serviceTicketData);

        $ticket2Data = $this->serviceTicketData;
        $ticket2Data['service_ticket_id'] = 'TKT-002';
        $ticket2Data['order_service_id'] = $orderService2->order_service_id;
        ServiceTicket::create($ticket2Data);

        // Act: Cari ticket berdasarkan nama customer
        $johnTickets = ServiceTicket::whereHas('orderService.customer', function ($query) {
            $query->where('name', 'like', '%John%');
        })->get();

        $janeTickets = ServiceTicket::whereHas('orderService.customer', function ($query) {
            $query->where('name', 'like', '%Jane%');
        })->get();

        // Assert: Verifikasi hasil pencarian
        $this->assertCount(1, $johnTickets, 'Harus ditemukan 1 ticket untuk John');
        $this->assertCount(1, $janeTickets, 'Harus ditemukan 1 ticket untuk Jane');

        $this->assertEquals('TKT-001', $johnTickets->first()->service_ticket_id, 'Ticket John harus TKT-001');
        $this->assertEquals('TKT-002', $janeTickets->first()->service_ticket_id, 'Ticket Jane harus TKT-002');
    }
}
