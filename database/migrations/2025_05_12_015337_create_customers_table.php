<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->string('customer_id')->primary(); // Format: CSTDDMMYY001
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('password')->nullable();
            $table->timestamp('last_active')->nullable();
            $table->boolean('hasAccount')->default(false);
            $table->boolean('hasAddress')->default(false);
            $table->string('photo')->nullable();
            $table->enum('gender', ['pria', 'wanita'])->nullable();
            $table->string('contact', 20);
            $table->unsignedInteger('service_orders_count')->default(0); // jumlah servis
            $table->unsignedInteger('product_orders_count')->default(0); // jumlah produk
            $table->unsignedInteger('total_points')->default(0); // poin total
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
