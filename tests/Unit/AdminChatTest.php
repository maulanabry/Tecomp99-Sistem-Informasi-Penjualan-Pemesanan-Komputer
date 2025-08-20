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
 * Test untuk sistem live chat admin
 * Menggunakan in-memory SQLite database untuk performa testing yang lebih cepat
 */
class AdminChatTest extends TestCase
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
     * Buat tabel-tabel yang diperlukan untuk testing chat admin
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
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'last_seen_at' => now(),
        ]);

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

        // Buat chat sample
        $chat = Chat::create([
            'customer_id' => 'CST240101001',
            'admin_id' => 1,
            'last_message_at' => now(),
            'is_active' => true,
        ]);

        // Buat pesan sample
        ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => 'customer',
            'sender_id' => 'CST240101001',
            'message' => 'Halo admin, saya butuh bantuan',
            'message_type' => 'text',
            'is_read_by_admin' => false,
        ]);
    }

    /**
     * Test admin dapat melihat daftar customer yang pernah chat
     */
    public function test_admin_dapat_melihat_daftar_customer_chat(): void
    {
        $admin = Admin::find(1);

        // Ambil chat yang dimiliki admin
        $chats = $admin->chats()
            ->where('is_active', true)
            ->with(['customer', 'lastMessage'])
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Verifikasi admin memiliki chat
        $this->assertGreaterThan(0, $chats->count());
        $this->assertEquals('CST240101001', $chats->first()->customer_id);
        $this->assertEquals('John Doe', $chats->first()->customer->name);

        // Verifikasi relasi dengan customer
        $this->assertInstanceOf(Customer::class, $chats->first()->customer);
    }

    /**
     * Test admin dapat memulai chat dengan customer tertentu
     */
    public function test_admin_dapat_memulai_chat_dengan_customer(): void
    {
        $adminId = 1;
        $customerId = 'CST240101002'; // Customer yang belum punya chat

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
    }

    /**
     * Test admin dapat mengirim pesan ke customer
     */
    public function test_admin_dapat_mengirim_pesan_ke_customer(): void
    {
        $chat = Chat::first();
        $adminId = 1;
        $messageText = 'Halo, ada yang bisa saya bantu?';

        // Buat pesan baru dari admin
        $message = ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => 'admin',
            'sender_id' => $adminId,
            'message' => $messageText,
            'message_type' => 'text',
        ]);

        // Verifikasi pesan berhasil dibuat
        $this->assertInstanceOf(ChatMessage::class, $message);
        $this->assertEquals('admin', $message->sender_type);
        $this->assertEquals($adminId, $message->sender_id);
        $this->assertEquals($messageText, $message->message);

        // Verifikasi pesan tersimpan di database
        $this->assertDatabaseHas('chat_messages', [
            'chat_id' => $chat->id,
            'sender_type' => 'admin',
            'message' => $messageText,
        ]);
    }

    /**
     * Test admin dapat menandai pesan sebagai sudah dibaca
     */
    public function test_admin_dapat_menandai_pesan_sebagai_dibaca(): void
    {
        $chat = Chat::first();

        // Verifikasi ada pesan yang belum dibaca dari customer
        $unreadCount = $chat->unread_messages_for_admin;
        $this->assertGreaterThan(0, $unreadCount);

        // Tandai pesan sebagai sudah dibaca oleh admin
        $chat->markAsReadByAdmin();

        // Verifikasi pesan sudah ditandai sebagai dibaca
        $this->assertEquals(0, $chat->fresh()->unread_messages_for_admin);
        $this->assertNotNull($chat->fresh()->admin_last_read_at);

        // Verifikasi di database
        $this->assertDatabaseHas('chat_messages', [
            'chat_id' => $chat->id,
            'sender_type' => 'customer',
            'is_read_by_admin' => true,
        ]);
    }

    /**
     * Test admin dapat mendapatkan jumlah pesan yang belum dibaca
     */
    public function test_admin_dapat_mendapatkan_jumlah_pesan_belum_dibaca(): void
    {
        $admin = Admin::find(1);
        $chat = Chat::first();

        // Tambahkan beberapa pesan dari customer yang belum dibaca
        ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => 'customer',
            'sender_id' => 'CST240101001',
            'message' => 'Pesan kedua dari customer',
            'message_type' => 'text',
            'is_read_by_admin' => false,
        ]);

        ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_type' => 'customer',
            'sender_id' => 'CST240101001',
            'message' => 'Pesan ketiga dari customer',
            'message_type' => 'text',
            'is_read_by_admin' => false,
        ]);

        // Hitung total pesan yang belum dibaca untuk admin
        $unreadCount = ChatMessage::whereHas('chat', function ($query) use ($admin) {
            $query->where('admin_id', $admin->id);
        })
            ->where('sender_type', 'customer')
            ->where('is_read_by_admin', false)
            ->count();

        // Verifikasi jumlah pesan yang belum dibaca
        $this->assertEquals(3, $unreadCount); // 1 pesan awal + 2 pesan baru

        // Test juga melalui relasi chat
        $chatUnreadCount = $chat->unread_messages_for_admin;
        $this->assertEquals(3, $chatUnreadCount);
    }
}
