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
            $table->string('slug');
            $table->boolean('is_active')->default(true); // true = aktif, false = nonaktif
            $table->unsignedInteger('sold_count')->default(0); // jumlah servis yang telah dipesan
            $table->timestamps(); // created_at dan updated_at
            $table->softDeletes(); // deleted_at

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
