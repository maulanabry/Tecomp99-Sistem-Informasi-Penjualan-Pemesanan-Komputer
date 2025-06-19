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
            $table->unsignedBigInteger('admin_id');
            $table->foreign('admin_id')->references('id')->on('admins');
            $table->enum('status', ['Menunggu', 'Menuju Lokasi', 'Diproses', 'Diantar', 'Perlu Diambil', 'Selesai', 'Dibatalkan'])
                ->default('Menunggu'); // Default status is 'Menunggu'
            $table->date('schedule_date');
            $table->integer('estimation_days')->nullable(); // New column for estimation days
            $table->date('estimate_date')->nullable(); // New column for estimate date
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
