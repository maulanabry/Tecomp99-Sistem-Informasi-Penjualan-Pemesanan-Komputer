<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id', 20);
            $table->foreign('customer_id')
                ->references('customer_id')
                ->on('customers')
                ->onDelete('cascade');

            $table->unsignedInteger('province_id')->nullable();
            $table->string('province_name')->nullable();

            $table->unsignedInteger('city_id')->nullable();
            $table->string('city_name')->nullable();

            $table->unsignedInteger('district_id')->nullable();
            $table->string('district_name')->nullable();

            $table->unsignedInteger('subdistrict_id')->nullable();
            $table->string('subdistrict_name')->nullable();

            $table->string('postal_code', 10)->nullable();
            $table->text('detail_address')->nullable();
            $table->boolean('is_default')->default(true); // optional default address flag

            $table->timestamps();

            // Indexes for faster search/filtering
            $table->index('customer_id');
            $table->index('province_id');
            $table->index('city_id');
            $table->index('district_id');
            $table->index('subdistrict_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
