<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            // Check if foreign key exists before trying to drop it
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'sessions' 
                AND COLUMN_NAME = 'user_id' 
                AND CONSTRAINT_NAME LIKE '%foreign%'
            ");

            // Drop foreign key if it exists
            if (!empty($foreignKeys)) {
                $table->dropForeign(['user_id']);
            }

            // Change user_id from integer to string to support customer_id format
            $table->string('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            // Revert back to integer
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }
};
