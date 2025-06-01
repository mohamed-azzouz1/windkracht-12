<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentFieldsToRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (!Schema::hasColumn('registrations', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('is_paid');
            }
            
            if (!Schema::hasColumn('registrations', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('payment_method');
            }
            
            if (!Schema::hasColumn('registrations', 'payment_date')) {
                $table->dateTime('payment_date')->nullable()->after('payment_reference');
            }
            
            if (!Schema::hasColumn('registrations', 'payment_confirmed')) {
                $table->boolean('payment_confirmed')->default(false)->after('payment_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_reference',
                'payment_date',
                'payment_confirmed'
            ]);
        });
    }
}
