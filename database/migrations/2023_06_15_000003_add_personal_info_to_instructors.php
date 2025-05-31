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
        Schema::table('instructors', function (Blueprint $table) {
            if (!Schema::hasColumn('instructors', 'address')) {
                $table->string('address')->nullable();
            }
            if (!Schema::hasColumn('instructors', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('instructors', 'date_of_birth')) {
                $table->date('date_of_birth')->nullable();
            }
            if (!Schema::hasColumn('instructors', 'bsn')) {
                $table->string('bsn')->nullable();
            }
            if (!Schema::hasColumn('instructors', 'phone')) {
                $table->string('phone')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instructors', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'city',
                'date_of_birth',
                'bsn',
                'phone'
            ]);
        });
    }
};
