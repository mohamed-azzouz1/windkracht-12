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
            if (!Schema::hasColumn('instructors', 'specialization')) {
                $table->string('specialization')->nullable();
            }
            if (!Schema::hasColumn('instructors', 'biography')) {
                $table->text('biography')->nullable();
            }
            if (!Schema::hasColumn('instructors', 'certification')) {
                $table->string('certification')->nullable();
            }
            if (!Schema::hasColumn('instructors', 'years_of_experience')) {
                $table->integer('years_of_experience')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('instructors', function (Blueprint $table) {
            $table->dropColumn(['specialization', 'biography', 'certification', 'years_of_experience']);
        });
    }
};
