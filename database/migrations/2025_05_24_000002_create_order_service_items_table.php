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
            $table->bigIncrements('order_service_item_id')->primary();

            // Relasi ke order_services
            $table->string('order_service_id', 50);
            $table->foreign('order_service_id')->references('order_service_id')->on('order_services')->onDelete('cascade');

            // Polymorphic fields
            $table->string('item_type');     // contoh: App\Models\Product atau App\Models\Service
            $table->string('item_id', 50);   // ID dari item terkait

            // Informasi item
            $table->integer('price');
            $table->integer('quantity');
            $table->integer('item_total');

            // Timestamps
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table->foreign('order_service_id')->references('order_service_id')->on('order_services')->onDelete('cascade');
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
