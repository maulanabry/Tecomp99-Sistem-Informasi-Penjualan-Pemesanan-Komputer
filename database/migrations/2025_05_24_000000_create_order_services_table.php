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
            $table->enum('status_order', ['Menunggu', 'Diproses', 'Dibatalkan', 'Selesai']);
            $table->enum('status_payment', ['belum_dibayar', 'down_payment', 'cicilan', 'lunas', 'dibatalkan']);
            $table->text('complaints')->nullable();
            $table->enum('type', ['reguler', 'onsite']);
            $table->text('device');
            $table->text('note')->nullable();
            $table->boolean('hasTicket')->default(false);
            $table->boolean('hasDevice')->default(false);
            $table->integer('sub_total')->default(0);
            $table->integer('grand_total')->default(0);
            $table->integer('discount_amount')->default(0);

            // Add missing warranty and payment tracking fields
            $table->integer('warranty_period_months')->nullable();
            $table->timestamp('warranty_expired_at')->nullable();
            $table->integer('paid_amount')->default(0);
            $table->integer('remaining_balance')->default(0);
            $table->timestamp('last_payment_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Single foreign key constraint
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
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
