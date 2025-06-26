<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->enum('theme', ['light', 'dark', 'system'])->default('system')->after('role');
        });
    }

    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('theme');
        });
    }
};
