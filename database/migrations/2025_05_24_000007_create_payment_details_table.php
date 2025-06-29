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
        Schema::create('payment_details', function (Blueprint $table) {
            $table->string('payment_id')->primary();
            $table->string('order_product_id', 50)->nullable();
            $table->string('order_service_id', 50)->nullable();
            $table->enum('method', ['Tunai', 'Bank BCA', 'QRIS']);
            $table->integer('amount');
            $table->integer('change_returned')->nullable();
            $table->string('name', 255);
            $table->enum('status', ['menunggu', 'dibayar', 'gagal']);
            $table->enum('payment_type', ['full', 'down_payment']);
            $table->enum('order_type', ['produk', 'servis']);
            $table->string('proof_photo', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('order_product_id')
                ->references('order_product_id')
                ->on('order_products')
                ->onDelete('cascade');

            $table->foreign('order_service_id')
                ->references('order_service_id')
                ->on('order_services')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_details');
    }
};
