<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->string('order_product_id', 50)->primary(); // e.g. OPRDDMMYY001
            $table->string('customer_id'); // FK ke customers.customer_id

            $table->enum('status_order', ['menunggu', 'diproses', 'dikirim', 'selesai', 'dibatalkan']);
            $table->enum('status_payment', ['belum_dibayar', 'down_payment', 'lunas', 'dibatalkan']);

            $table->integer('sub_total');
            $table->integer('discount_amount')->nullable();
            $table->integer('grand_total');
            $table->integer('shipping_cost')->nullable();
            $table->enum('type', ['langsung', 'pengiriman']);
            $table->text('note')->nullable();

            // Add missing warranty and payment tracking fields
            $table->integer('warranty_period_months')->nullable();
            $table->timestamp('warranty_expired_at')->nullable();
            $table->integer('paid_amount')->default(0);
            $table->integer('remaining_balance')->default(0);
            $table->timestamp('last_payment_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
