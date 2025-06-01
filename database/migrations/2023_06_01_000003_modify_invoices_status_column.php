<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ModifyInvoicesStatusColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the table exists
        if (Schema::hasTable('invoices')) {
            // First get the current structure of the status column
            $columnType = DB::getSchemaBuilder()->getColumnType('invoices', 'status');
            
            // If it's an enum, change it to varchar
            if ($columnType === 'enum') {
                // We need to use raw SQL because Laravel doesn't directly support altering ENUM to VARCHAR
                DB::statement('ALTER TABLE invoices MODIFY status VARCHAR(50) NOT NULL');
            }
            // If it's varchar but too small, increase the size
            else if ($columnType === 'string' || $columnType === 'varchar' || $columnType === 'char') {
                Schema::table('invoices', function (Blueprint $table) {
                    $table->string('status', 50)->change();
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // We don't want to revert this because it might cause data loss
    }
}
