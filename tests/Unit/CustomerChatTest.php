<?php

namespace Tests\Unit;

use App\Models\Admin;
use App\Models\Customer;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Event;

/**
 * Test untuk sistem live chat customer
 * Menggunakan in-memory SQLite database untuk performa testing yang lebih cepat
 */
class CustomerChatTest extends TestCase
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
     * Buat tabel-tabel yang diperlukan untuk testing chat customer
     */
    private function createRequiredTables(): void
    {
        // Tabel admins
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role')->default('admin');
            $table->timestamp('last_seen_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

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

        // Tabel chats
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id');
            $table->unsignedBigInteger('admin_id');
            $table->timestamp('last_message_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('customer_last_read_at')->nullable();
            $table->timestamp('admin_last_read_at')->nullable();
            $table->timestamps();

            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->foreign('admin_id')->references('id')->on('admins');
        });

        // Tabel chat_messages
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id');
            $table->enum('sender_type', ['customer', 'admin']);
            $table->string('sender_id');
            $table->text('message');
            $table->enum('message_type', ['text', 'image', 'file'])->default('text');
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->boolean('is_read_by_customer')->default(false);
            $table->boolean('is_read_by_admin')->default(false);
            $table->timestamp('read_by_customer_at')->nullable();
            $table->timestamp('read_by_admin_at')->nullable();
            $table->timestamps();

            $table->foreign('chat_id')->references('id')->on('chats');
        });
    }

    /**
     * Buat data sample untuk testing
     */
    private function createSampleData(): void
    {
        // Buat admin sample
        Admin::create([
            'id' => 1,
            'name' => 'Admin Support',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'last_seen_at' => now()->subMinutes(5), // Online
        ]);

        Admin::create([
            'id' => 2,
            'name' => 'Admin Technical',
            'email' => 'tech@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'last_seen_at' => now()->subHours(2), // Offline
        ]);

        // Buat customer sample
        Customer::create([
            'customer_id' => 'CST240101001',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'contact' => '081234567890',
            'hasAccount' => true,
        ]);

        // Buat chat sample dengan admin
        $chat = Chat::create([
            'customer_id' => 'CST240101001',
            'admin_id' => 1,
            'last_message_at' => now(),
            'is_active' => true,
        ]);

        // Buat pesan sample dari admin
        ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => 'admin',
            'sender_id' => '1',
            'message' => 'Halo, selamat datang! Ada yang bisa kami bantu?',
            'message_type' => 'text',
            'is_read_by_customer' => false,
        ]);
    }

    /**
     * Test customer dapat melihat daftar admin yang tersedia
     */
    public function test_customer_dapat_melihat_daftar_admin_tersedia(): void
    {
        // Ambil daftar admin yang tersedia
        $admins = Admin::where('role', 'admin')->get();

        // Verifikasi ada admin yang tersedia
        $this->assertGreaterThan(0, $admins->count());
        $this->assertEquals(2, $admins->count());

        // Verifikasi data admin
        $adminSupport = $admins->where('name', 'Admin Support')->first();
        $this->assertNotNull($adminSupport);
        $this->assertEquals('admin@test.com', $adminSupport->email);

        // Test status online admin (simulasi method isOnline)
        $recentlyActive = $adminSupport->last_seen_at &&
            $adminSupport->last_seen_at->diffInMinutes(now()) <= 15;
        $this->assertTrue($recentlyActive); // Admin Support online

        $adminTech = $admins->where('name', 'Admin Technical')->first();
        $offlineAdmin = $adminTech->last_seen_at &&
            $adminTech->last_seen_at->diffInMinutes(now()) > 15;
        $this->assertTrue($offlineAdmin); // Admin Technical offline
    }

    /**
     * Test customer dapat memulai chat dengan admin
     */
    public function test_customer_dapat_memulai_chat_dengan_admin(): void
    {
        $customerId = 'CST240101001';
        $adminId = 2; // Admin yang belum punya chat dengan customer

        // Cari atau buat chat baru
        $chat = Chat::findOrCreateChat($customerId, $adminId);

        // Verifikasi chat berhasil dibuat
        $this->assertInstanceOf(Chat::class, $chat);
        $this->assertEquals($customerId, $chat->customer_id);
        $this->assertEquals($adminId, $chat->admin_id);
        $this->assertTrue($chat->is_active);

        // Verifikasi chat tersimpan di database
        $this->assertDatabaseHas('chats', [
            'customer_id' => $customerId,
            'admin_id' => $adminId,
            'is_active' => true,
        ]);

        // Test mendapatkan chat yang sudah ada
        $existingChat = Chat::findOrCreateChat($customerId, 1); // Admin yang sudah ada chat
        $this->assertEquals(1, $existingChat->admin_id);

        // Verifikasi tidak membuat chat duplikat
        $chatCount = Chat::where('customer_id', $customerId)
            ->where('admin_id', 1)
            ->count();
        $this->assertEquals(1, $chatCount);
    }

    /**
     * Test customer dapat mengirim pesan ke admin
     */
    public function test_customer_dapat_mengirim_pesan_ke_admin(): void
    {
        $chat = Chat::first();
        $customerId = 'CST240101001';
        $messageText = 'Halo admin, saya butuh bantuan dengan laptop saya';

        // Buat pesan baru dari customer
        $message = ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => 'customer',
            'sender_id' => $customerId,
            'message' => $messageText,
            'message_type' => 'text',
        ]);

        // Verifikasi pesan berhasil dibuat
        $this->assertInstanceOf(ChatMessage::class, $message);
        $this->assertEquals('customer', $message->sender_type);
        $this->assertEquals($customerId, $message->sender_id);
        $this->assertEquals($messageText, $message->message);

        // Verifikasi pesan tersimpan di database
        $this->assertDatabaseHas('chat_messages', [
            'chat_id' => $chat->id,
            'sender_type' => 'customer',
            'message' => $messageText,
        ]);

        // Update last_message_at pada chat
        $chat->update(['last_message_at' => now()]);
        $this->assertNotNull($chat->fresh()->last_message_at);
    }

    /**
     * Test customer dapat melihat riwayat chat
     */
    public function test_customer_dapat_melihat_riwayat_chat(): void
    {
        $customerId = 'CST240101001';
        $customer = Customer::find($customerId);
        $chat = Chat::first();

        // Tambahkan beberapa pesan untuk riwayat
        ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => 'customer',
            'sender_id' => $customerId,
            'message' => 'Laptop saya tidak bisa menyala',
            'message_type' => 'text',
        ]);

        ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => 'admin',
            'sender_id' => '1',
            'message' => 'Baik, bisa dijelaskan lebih detail masalahnya?',
            'message_type' => 'text',
        ]);

        // Ambil riwayat chat customer
        $chats = $customer->chats()
            ->where('is_active', true)
            ->with(['admin', 'messages'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Verifikasi customer memiliki chat
        $this->assertGreaterThan(0, $chats->count());
        $this->assertEquals(1, $chats->count());

        // Verifikasi relasi dengan admin
        $firstChat = $chats->first();
        $this->assertInstanceOf(Admin::class, $firstChat->admin);
        $this->assertEquals('Admin Support', $firstChat->admin->name);

        // Verifikasi pesan dalam chat
        $messages = $firstChat->messages()->orderBy('created_at', 'asc')->get();
        $this->assertGreaterThan(0, $messages->count());
        $this->assertEquals(3, $messages->count()); // 1 pesan awal + 2 pesan baru

        // Verifikasi urutan pesan
        $this->assertEquals('admin', $messages->first()->sender_type);
        $this->assertEquals('customer', $messages->get(1)->sender_type);
        $this->assertEquals('admin', $messages->last()->sender_type);
    }

    /**
     * Test customer dapat menandai pesan sebagai sudah dibaca
     */
    public function test_customer_dapat_menandai_pesan_sebagai_dibaca(): void
    {
        $chat = Chat::first();
        $customerId = 'CST240101001';

        // Verifikasi ada pesan yang belum dibaca dari admin
        $unreadCount = $chat->unread_messages_for_customer;
        $this->assertGreaterThan(0, $unreadCount);

        // Tandai pesan sebagai sudah dibaca oleh customer
        $chat->markAsReadByCustomer();

        // Verifikasi pesan sudah ditandai sebagai dibaca
        $this->assertEquals(0, $chat->fresh()->unread_messages_for_customer);
        $this->assertNotNull($chat->fresh()->customer_last_read_at);

        // Verifikasi di database
        $this->assertDatabaseHas('chat_messages', [
            'chat_id' => $chat->id,
            'sender_type' => 'admin',
            'is_read_by_customer' => true,
        ]);

        // Test hitung total pesan belum dibaca untuk customer
        $customer = Customer::find($customerId);

        // Tambahkan pesan baru dari admin
        ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => 'admin',
            'sender_id' => '1',
            'message' => 'Pesan baru dari admin',
            'message_type' => 'text',
            'is_read_by_customer' => false,
        ]);

        // Hitung pesan belum dibaca
        $totalUnread = ChatMessage::whereHas('chat', function ($query) use ($customerId) {
            $query->where('customer_id', $customerId);
        })
            ->where('sender_type', 'admin')
            ->where('is_read_by_customer', false)
            ->count();

        $this->assertEquals(1, $totalUnread);

        // Test melalui accessor di model Customer
        $customerUnreadCount = $customer->unread_messages_count;
        $this->assertEquals(1, $customerUnreadCount);
    }
}
