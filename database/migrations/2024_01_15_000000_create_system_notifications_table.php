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
        Schema::create('system_notifications', function (Blueprint $table) {
            $table->id();
            $table->morphs('notifiable'); // notifiable_id, notifiable_type (sudah include index)
            $table->string('type'); // notification type enum
            $table->string('subject_id'); // subject_id as string to support custom primary keys
            $table->string('subject_type'); // subject_type (related model class)
            $table->text('message'); // human readable message
            $table->json('data')->nullable(); // additional context data
            $table->timestamp('read_at')->nullable(); // when notification was read
            $table->timestamps();

            // Indexes untuk performa query (morphs sudah membuat index untuk notifiable)
            $table->index(['subject_type', 'subject_id']);
            $table->index(['type']);
            $table->index(['read_at']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_notifications');
    }
};
