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
        Schema::create('order_service_images', function (Blueprint $table) {
            $table->integer('media_id')->primary();
            $table->string('order_service_id', 50);
            $table->foreign('order_service_id')->references('order_service_id')->on('order_services')->onDelete('cascade');
            $table->string('url', 255);
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_service_images');
    }
};
