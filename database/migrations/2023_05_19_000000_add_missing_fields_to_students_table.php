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
        // Get all existing column names from the students table
        $columns = DB::connection()->getSchemaBuilder()->getColumnListing('students');
        
        Schema::table('students', function (Blueprint $table) use ($columns) {
            if (!in_array('address', $columns)) {
                $table->string('address')->nullable();
            }
            if (!in_array('city', $columns)) {
                $table->string('city')->nullable();
            }
            if (!in_array('postal_code', $columns)) {
                $table->string('postal_code')->nullable();
            }
            if (!in_array('phone', $columns)) {
                $table->string('phone')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Get all existing column names
        $columns = DB::connection()->getSchemaBuilder()->getColumnListing('students');
        
        // Only drop columns that exist
        Schema::table('students', function (Blueprint $table) use ($columns) {
            $columnsToCheck = ['address', 'city', 'postal_code', 'phone'];
            
            foreach ($columnsToCheck as $column) {
                if (in_array($column, $columns)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
