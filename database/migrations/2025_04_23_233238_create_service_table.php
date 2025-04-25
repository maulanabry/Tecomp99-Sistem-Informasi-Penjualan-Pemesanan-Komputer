<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_service_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('service', function (Blueprint $table) {
            $table->id('service_id');
            $table->foreignId('categories_id')->constrained('categories', 'categories_id');
            $table->string('name');
            $table->text('description');
            $table->integer('price');
            $table->string('thumbnail');
            $table->string('slug');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service');
    }
};
