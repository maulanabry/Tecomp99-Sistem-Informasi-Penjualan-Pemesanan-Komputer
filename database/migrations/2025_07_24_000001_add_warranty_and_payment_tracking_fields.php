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
        // Add warranty and payment tracking fields to order_products
        Schema::table('order_products', function (Blueprint $table) {
            $table->integer('warranty_period_months')->nullable()->after('note');
            $table->timestamp('warranty_expired_at')->nullable()->after('warranty_period_months');
            $table->decimal('paid_amount', 12, 2)->default(0)->after('grand_total');
            $table->decimal('remaining_balance', 12, 2)->default(0)->after('paid_amount');
            $table->timestamp('last_payment_at')->nullable()->after('remaining_balance');
        });

        // Add warranty and payment tracking fields to order_services
        Schema::table('order_services', function (Blueprint $table) {
            $table->integer('warranty_period_months')->nullable()->after('note');
            $table->timestamp('warranty_expired_at')->nullable()->after('warranty_period_months');
            $table->decimal('paid_amount', 12, 2)->default(0)->after('grand_total');
            $table->decimal('remaining_balance', 12, 2)->default(0)->after('paid_amount');
            $table->timestamp('last_payment_at')->nullable()->after('remaining_balance');
        });

        // Add change_returned field to payment_details
        Schema::table('payment_details', function (Blueprint $table) {
            $table->decimal('change_returned', 12, 2)->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn([
                'warranty_period_months',
                'warranty_expired_at',
                'paid_amount',
                'remaining_balance',
                'last_payment_at'
            ]);
        });

        Schema::table('order_services', function (Blueprint $table) {
            $table->dropColumn([
                'warranty_period_months',
                'warranty_expired_at',
                'paid_amount',
                'remaining_balance',
                'last_payment_at'
            ]);
        });

        Schema::table('payment_details', function (Blueprint $table) {
            $table->dropColumn('change_returned');
        });
    }
};
