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
        Schema::create('order_services', function (Blueprint $table) {
            $table->string('order_service_id', 50)->primary();
            $table->string('customer_id', 50);
            $table->foreign('customer_id')->references('customer_id')->on('customers');
            $table->enum('status_order', ['Menunggu', 'Diproses', 'Konfirmasi', 'Diantar', 'Perlu Diambil', 'Dibatalkan', 'Selesai']);
            $table->enum('status_payment', ['belum_dibayar', 'down_payment', 'lunas', 'dibatalkan']);
            $table->text('complaints')->nullable();
            $table->enum('type', ['reguler', 'onsite']);
            $table->text('device');
            $table->text('note')->nullable();
            $table->integer('sub_total')->default(0);
            $table->integer('grand_total_amount')->default(0);
            $table->integer('discount_amount')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_services');
    }
};
