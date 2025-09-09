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
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->enum('status', [
                'Menunggu',             // order baru masuk
                'Dijadwalkan',          // sudah ada jadwal servis
                'Menuju_lokasi',        // teknisi OTW ke lokasi customer (onsite)
                'Diproses',             // servis sedang dikerjakan
                'Menunggu_sparepart',   // pending sparepart
                'Siap_diambil',         // selesai, barang bisa diambil
                'Diantar',              // selesai, barang sedang diantar ke customer
                'Selesai',              // servis beres dan diterima customer
                'Dibatalkan',           // order dibatalkan
                'Expired'               // order hangus (tidak dibayar/diambil)
            ])
                ->default('Menunggu');
            $table->date('schedule_date');
            $table->integer('estimation_days')->nullable();
            $table->date('estimate_date')->nullable();
            $table->dateTime('visit_schedule')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints
            $table->foreign('order_service_id')->references('order_service_id')->on('order_services')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('restrict');
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
