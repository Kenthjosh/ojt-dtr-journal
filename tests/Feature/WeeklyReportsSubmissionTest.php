<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\WeeklyReports;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

it('a user can submit a weekly report', function () {
    $report = WeeklyReports::create([
        'user_id' => $this->user->id,
        'week_start' => now()->startOfWeek(),
        'week_end' => now()->endOfWeek(),
        'journal_number' => 1,
        'entries' => [
            'week_focus' => 'Learning Laravel',
            'topics_learned' => ['Eloquent', 'Filament forms'],
            'outputs_links' => [
                ['url' => 'http://example.com', 'description' => 'Project link'],
            ],
            'what_built' => 'Weekly journal system',
            'decisions_reasoning' => [
                'decision_1' => 'Use JSON column for entries',
                'decision_2' => 'Filament form for UI',
            ],
            'challenges_blockers' => 'Validation issues initially',
            'improve_next_time' => [
                'improvement_1' => 'Add more UI guidance',
                'improvement_2' => 'Use better validation messages',
            ],
            'key_takeaway' => 'Complex forms are manageable with Filament',
        ],
        'status' => 'pending',
    ]);

    $this->assertDatabaseHas('weekly_reports', [
        'id' => $report->id,
        'user_id' => $this->user->id,
        'status' => 'pending',
    ]);
});
