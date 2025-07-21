<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id'); // Foreign key ke chats table
            $table->string('sender_type'); // 'customer' atau 'admin'
            $table->string('sender_id'); // ID pengirim (customer_id atau admin_id)
            $table->text('message'); // Isi pesan
            $table->enum('message_type', ['text', 'image', 'file'])->default('text'); // Jenis pesan
            $table->string('file_path')->nullable(); // Path file jika ada attachment
            $table->string('file_name')->nullable(); // Nama file asli
            $table->boolean('is_read')->default(false); // Status sudah dibaca
            $table->timestamp('read_at')->nullable(); // Waktu dibaca
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('chat_id')->references('id')->on('chats')->onDelete('cascade');

            // Index untuk performa
            $table->index(['chat_id', 'created_at']);
            $table->index(['sender_type', 'sender_id']);
            $table->index('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
