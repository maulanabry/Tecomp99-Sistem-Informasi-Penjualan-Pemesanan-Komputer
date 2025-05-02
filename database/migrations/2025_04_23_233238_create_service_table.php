<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service', function (Blueprint $table) {
            $table->string('service_id')->primary();
            $table->unsignedBigInteger('categories_id');
            $table->string('name');
            $table->text('description');
            $table->integer('price');
            $table->string('thumbnail');
            $table->string('slug')->unique();
            $table->timestamps();

            $table->foreign('categories_id')
                ->references('categories_id')
                ->on('categories')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service');
    }
};
