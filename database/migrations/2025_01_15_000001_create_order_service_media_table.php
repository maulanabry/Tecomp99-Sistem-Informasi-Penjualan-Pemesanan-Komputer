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
        Schema::create('order_service_media', function (Blueprint $table) {
            $table->id('order_service_media_id');
            $table->string('order_service_id');
            $table->string('media_path');
            $table->string('media_name');
            $table->string('file_type', 10);
            $table->bigInteger('file_size');
            $table->boolean('is_main')->default(false);
            $table->timestamps();

            $table->foreign('order_service_id')->references('order_service_id')->on('order_services')->onDelete('cascade');
            $table->index(['order_service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_service_media');
    }
};
