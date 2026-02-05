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
        Schema::create('weekly_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->unique();
            $table->date('week_start');
            $table->date('week_end');
            $table->enum('status', ['pending', 'viewed', 'certified']);
            $table->datetime('submitted_at')->nullable();   
            $table->datetime('viewed_at')->nullable();
            $table->datetime('certified_at')->nullable();
            $table->foreignId('certified_by')->nullable()->constrained('users');
            $table->string('signature')->nullable();
            $table->json('entries')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_reports');
    }
};
