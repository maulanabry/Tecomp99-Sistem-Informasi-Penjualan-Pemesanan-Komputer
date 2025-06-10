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
        Schema::create('service_actions', function (Blueprint $table) {
            $table->string('service_action_id')->primary();
            $table->string('service_ticket_id');
            $table->foreign('service_ticket_id')->references('service_ticket_id')->on('service_tickets');
            $table->text('action');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_actions');
    }
};
