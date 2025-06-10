<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('shipping', function (Blueprint $table) {
            $table->id('shipping_id');
            $table->string('order_product_id');
            $table->string('courier_name', 100)->default('JNE');
            $table->string('courier_service', 100)->default('REG');
            $table->string('tracking_number', 100)->unique()->nullable();
            $table->enum('status', ['menunggu', 'dikirim', 'diterima', 'dibatalkan'])->default('menunggu');
            $table->integer('shipping_cost')->default(0);
            $table->integer('total_weight')->default(0);
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
            $table->softDeletes(); // Adds the `deleted_at` column

            $table->foreign('order_product_id')
                ->references('order_product_id')
                ->on('order_products')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('shipping', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Removes the `deleted_at` column
        });
    }
};
