<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->string('product_id')->primary();
            $table->unsignedBigInteger('categories_id');
            $table->unsignedBigInteger('brand_id'); // Ganti dari 'brand' ke 'brand_id'
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('price');
            $table->integer('stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('sold_count')->default(0);
            $table->string('slug')->unique();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('categories_id')->references('categories_id')->on('categories')->onDelete('cascade');
            $table->foreign('brand_id')->references('brand_id')->on('brands')->onDelete('cascade'); // Update di sini
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
