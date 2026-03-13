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
            $table->renameColumn('session_1_start', 'start_time');
            $table->renameColumn('session_1_end', 'end_time');
            $table->renameColumn('session_2_start', 'break_start');
            $table->renameColumn('session_2_end', 'break_end');
        });
    }

    public function down(): void
    {
        Schema::table('shifts', function (Blueprint $table) {
            $table->renameColumn('start_time', 'session_1_start');
            $table->renameColumn('end_time', 'session_1_end');
            $table->renameColumn('break_start', 'session_2_start');
            $table->renameColumn('break_end', 'session_2_end');
        });
    }
};
