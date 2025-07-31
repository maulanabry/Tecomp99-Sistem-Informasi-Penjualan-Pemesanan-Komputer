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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id', 50);
            $table->string('product_id', 50);
            $table->integer('quantity')->default(1);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('customer_id')
                ->references('customer_id')
                ->on('customers')
                ->onDelete('cascade');

            $table->foreign('product_id')
                ->references('product_id')
                ->on('products')
                ->onDelete('cascade');

            // Unique constraint untuk mencegah duplikasi item yang sama
            $table->unique(['customer_id', 'product_id']);

            // Indexes untuk performa yang lebih baik
            $table->index('customer_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
