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
        Schema::create('order_service_items', function (Blueprint $table) {
            $table->bigIncrements('order_service_item_id');
            $table->string('order_service_id', 50);
            $table->foreign('order_service_id')->references('order_service_id')->on('order_services');
            // Changed service_id to string(50) to match service table primary key type and table name corrected
            $table->string('service_id', 50);
            $table->foreign('service_id')->references('service_id')->on('service');
            // Changed product_id to string(50) to match products table primary key type
            $table->string('product_id', 50)->nullable();
            $table->foreign('product_id')->references('product_id')->on('products');
            $table->text('description');
            $table->integer('price');
            $table->integer('quantity');
            $table->integer('item_total');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_service_items');
    }
};
