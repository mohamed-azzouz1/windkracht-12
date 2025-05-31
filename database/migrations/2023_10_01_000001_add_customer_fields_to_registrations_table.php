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
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('location')->nullable();
            $table->string('duo_name')->nullable();
            $table->string('duo_email')->nullable();
            $table->string('duo_phone')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->string('cancellation_type')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamp('payment_reported_at')->nullable();
            $table->timestamp('payment_verified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn([
                'location',
                'duo_name',
                'duo_email',
                'duo_phone',
                'cancellation_reason',
                'cancellation_type',
                'cancelled_at',
                'payment_date',
                'payment_reference',
                'payment_reported_at',
                'payment_verified_at'
            ]);
        });
    }
};
