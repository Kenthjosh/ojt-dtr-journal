<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_activities', function (Blueprint $table) {
            // Add polymorphic columns first
            $table->unsignedBigInteger('subject_id_new')->nullable();
            $table->string('subject_type_new')->nullable();
        });

        // Migrate existing subject_id data
        DB::table('admin_activities')->get()->each(function ($activity) {
            // Check if the subject_id points to a user or a weekly report
            $userExists = DB::table('users')->where('id', $activity->subject_id)->exists();
            $weeklyReportExists = DB::table('weekly_reports')->where('id', $activity->subject_id)->exists();

            if ($userExists) {
                DB::table('admin_activities')
                    ->where('id', $activity->id)
                    ->update([
                        'subject_id_new' => $activity->subject_id,
                        'subject_type_new' => 'App\\Models\\User',
                    ]);
            } elseif ($weeklyReportExists) {
                DB::table('admin_activities')
                    ->where('id', $activity->id)
                    ->update([
                        'subject_id_new' => $activity->subject_id,
                        'subject_type_new' => 'App\\Models\\WeeklyReport',
                    ]);
            } else {
                // If it points nowhere, leave null
                DB::table('admin_activities')
                    ->where('id', $activity->id)
                    ->update([
                        'subject_id_new' => null,
                        'subject_type_new' => null,
                    ]);
            }
        });

        Schema::table('admin_activities', function (Blueprint $table) {
            // Drop old foreign key and column
            $table->dropForeign(['subject_id']);
            $table->dropColumn('subject_id');
            $table->dropColumn('subject_type');

            // Rename new polymorphic columns
            $table->renameColumn('subject_id_new', 'subject_id');
            $table->renameColumn('subject_type_new', 'subject_type');

            // Add index for polymorphic queries
            $table->index(['subject_id', 'subject_type']);
        });
    }

    public function down(): void
    {
        Schema::table('admin_activities', function (Blueprint $table) {
            $table->dropIndex(['subject_id', 'subject_type']);
            $table->string('subject_type')->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();
        });
    }
};
