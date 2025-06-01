<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class FixInvoicesStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // First, check if the table exists
        if (Schema::hasTable('invoices')) {
            // Use a raw SQL query that works regardless of the column type
            DB::statement("ALTER TABLE invoices MODIFY status VARCHAR(50)");
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // We don't want to reverse this change as it might cause data loss
    }
}
