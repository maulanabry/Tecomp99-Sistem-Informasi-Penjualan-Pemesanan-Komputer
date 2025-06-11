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
        Schema::create('service_tickets', function (Blueprint $table) {
            $table->string('service_ticket_id')->primary();
            $table->string('order_service_id', 50);
            $table->foreign('order_service_id')->references('order_service_id')->on('order_services');
            // Changed admin_id to unsignedBigInteger to match admins table primary key type
            $table->unsignedBigInteger('admin_id');
            $table->foreign('admin_id')->references('id')->on('admins');
            $table->enum('status', ['Menunggu', 'Menuju Lokasi', 'Diproses', 'Diantar', 'Perlu Diambil', 'Selesai']);
            $table->dateTime('schedule_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_tickets');
    }
};
