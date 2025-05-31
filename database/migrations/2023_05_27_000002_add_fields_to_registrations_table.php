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
            // Change start_date and end_date to datetime for time precision
            $table->dateTime('start_date')->change();
            $table->dateTime('end_date')->nullable()->change();

            // Add payment status fields
            $table->boolean('is_paid')->default(false);
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Change back to date fields
            $table->date('start_date')->change();
            $table->date('end_date')->nullable()->change();
            
            // Remove added fields
            $table->dropColumn([
                'is_paid',
                'payment_date',
                'payment_reference',
                'payment_reported_at',
                'payment_verified_at',
                'location',
                'duo_name',
                'duo_email',
                'duo_phone',
                'cancellation_reason',
                'cancellation_type',
                'cancelled_at'
            ]);
        });
    }
};
