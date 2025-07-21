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
        Schema::table('chat_messages', function (Blueprint $table) {
            // Menambahkan kolom untuk status baca terpisah untuk customer dan admin
            $table->boolean('is_read_by_customer')->default(false)->after('is_read');
            $table->boolean('is_read_by_admin')->default(false)->after('is_read_by_customer');
            $table->timestamp('read_by_customer_at')->nullable()->after('is_read_by_admin');
            $table->timestamp('read_by_admin_at')->nullable()->after('read_by_customer_at');

            // Menambahkan index untuk performa
            $table->index('is_read_by_customer');
            $table->index('is_read_by_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex(['is_read_by_customer']);
            $table->dropIndex(['is_read_by_admin']);
            $table->dropColumn([
                'is_read_by_customer',
                'is_read_by_admin',
                'read_by_customer_at',
                'read_by_admin_at'
            ]);
        });
    }
};
