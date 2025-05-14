<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('promos', function (Blueprint $table) {
            $table->id('promo_id');
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->enum('type', ['amount', 'percentage']);
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->integer('discount_amount')->nullable();
            $table->integer('minimum_order_amount')->nullable();
            $table->boolean('is_active')->default(false);
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
