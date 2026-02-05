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

it('a user cannot edit a report after certification', function () {
    $report = WeeklyReports::create([
        'user_id' => $this->user->id,
        'week_start' => now()->startOfWeek(),
        'week_end' => now()->endOfWeek(),
        'journal_number' => 1,
        'entries' => [
            'week_focus' => 'Initial Focus',
            'topics_learned' => ['Laravel'],
            'outputs_links' => [
                ['url' => 'http://example.com', 'description' => 'Example link']
            ],
            'what_built' => 'Weekly report system',
            'decisions_reasoning' => ['decision_1' => 'A', 'decision_2' => 'B'],
            'challenges_blockers' => 'None',
            'improve_next_time' => ['improvement_1' => 'X', 'improvement_2' => 'Y'],
            'key_takeaway' => 'Test takeaway',
        ],
        'status' => 'certified',
    ]);

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Cannot modify a certified report.');

    // Attempt to update
    $report->status = 'pending';
    $report->save(); // Will throw the certified exception immediately
});
