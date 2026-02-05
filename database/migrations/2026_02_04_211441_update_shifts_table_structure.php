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
        Schema::table('shifts', function (Blueprint $table) {
            // Remove the old columns
            $table->dropColumn(['start_time', 'end_time', 'break_start', 'break_end']);

            // Add the new precise session columns
            $table->time('session_1_start')->nullable();
            $table->time('session_1_end')->nullable();
            $table->time('session_2_start')->nullable();
            $table->time('session_2_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            // If we rollback, put the old columns back
            $table->dropColumn(['session_1_start', 'session_1_end', 'session_2_start', 'session_2_end']);

            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
        });
    }
};
