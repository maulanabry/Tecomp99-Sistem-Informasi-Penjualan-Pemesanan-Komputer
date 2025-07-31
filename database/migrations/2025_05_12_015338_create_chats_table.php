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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id'); // Foreign key ke customers table
            $table->unsignedBigInteger('admin_id'); // Foreign key ke admins table
            $table->timestamp('last_message_at')->nullable(); // Waktu pesan terakhir
            $table->boolean('is_active')->default(true); // Status chat aktif
            $table->timestamp('customer_last_read_at')->nullable(); // Kapan customer terakhir baca
            $table->timestamp('admin_last_read_at')->nullable(); // Kapan admin terakhir baca
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');

            // Index untuk performa
            $table->index(['customer_id', 'admin_id']);
            $table->index('last_message_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
