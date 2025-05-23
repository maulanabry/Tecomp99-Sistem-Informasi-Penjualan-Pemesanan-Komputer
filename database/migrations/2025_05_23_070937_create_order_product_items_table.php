<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_product_items', function (Blueprint $table) {
            $table->id('order_product_item_id');
            $table->string('order_product_id', 50);
            $table->string('product_id'); // FK ke products.product_id

            $table->integer('quantity');
            $table->integer('price');
            $table->integer('item_total');

            $table->timestamps();

            $table->foreign('order_product_id')->references('order_product_id')->on('order_products')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_product_items');
    }
};
