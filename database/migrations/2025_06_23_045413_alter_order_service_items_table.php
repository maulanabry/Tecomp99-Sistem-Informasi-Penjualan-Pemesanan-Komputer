<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('order_service_items', function (Blueprint $table) {
            // Drop old columns
            $table->dropForeign(['service_id']);
            $table->dropColumn('service_id');

            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');

            $table->dropColumn('description');

            // Add new polymorphic columns
            $table->string('item_type')->after('order_service_id');
            $table->string('item_id', 50)->after('item_type');

            // Add updated_at
            $table->timestamp('updated_at')->nullable()->after('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('order_service_items', function (Blueprint $table) {
            // Rollback: Add back removed columns
            $table->string('service_id', 50)->nullable()->after('order_service_id');
            $table->foreign('service_id')->references('service_id')->on('service');

            $table->string('product_id', 50)->nullable()->after('service_id');
            $table->foreign('product_id')->references('product_id')->on('products');

            $table->text('description')->nullable()->after('product_id');

            // Drop new polymorphic columns
            $table->dropColumn(['item_type', 'item_id', 'updated_at']);
        });
    }
};
