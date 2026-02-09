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

// week_focus
it('JSON entries must contain week_focus key', function () {
    $this->expectException(\InvalidArgumentException::class);

    WeeklyReports::create([
        'user_id' => $this->user->id,
        'week_start' => now()->startOfWeek(),
        'week_end' => now()->endOfWeek(),
        'journal_number' => 1,
        'entries' => [
            'topics_learned' => ['Eloquent'],
        ],
        'status' => 'pending',
    ]);
});

// topics_learned
it('topics_learned must exist and cannot be empty', function () {
    $this->expectException(\InvalidArgumentException::class);

    WeeklyReports::create([
        'user_id' => $this->user->id,
        'week_start' => now()->startOfWeek(),
        'week_end' => now()->endOfWeek(),
        'journal_number' => 1,
        'entries' => [
            'week_focus' => 'Learning Laravel',
            'topics_learned' => [], // empty array
        ],
        'status' => 'pending',
    ]);
});

// outputs_links
it('outputs_links must exist', function () {
    $this->expectException(\InvalidArgumentException::class);

    WeeklyReports::create([
        'user_id' => $this->user->id,
        'week_start' => now()->startOfWeek(),
        'week_end' => now()->endOfWeek(),
        'journal_number' => 1,
        'entries' => [
            'week_focus' => 'Learning Laravel',
            'topics_learned' => ['Eloquent'],
            // outputs_links missing
        ],
        'status' => 'pending',
    ]);
});

it('each outputs_links object must have url and description', function () {
    $this->expectException(\InvalidArgumentException::class);

    WeeklyReports::create([
        'user_id' => $this->user->id,
        'week_start' => now()->startOfWeek(),
        'week_end' => now()->endOfWeek(),
        'journal_number' => 1,
        'entries' => [
            'week_focus' => 'Learning Laravel',
            'topics_learned' => ['Eloquent'],
            'outputs_links' => [
                ['url' => 'http://example.com'], // missing description
            ],
        ],
        'status' => 'pending',
    ]);
});

// what_built
it('what_built cannot be empty', function () {
    $this->expectException(\InvalidArgumentException::class);

    WeeklyReports::create([
        'user_id' => $this->user->id,
        'week_start' => now()->startOfWeek(),
        'week_end' => now()->endOfWeek(),
        'journal_number' => 1,
        'entries' => [
            'week_focus' => 'Learning Laravel',
            'topics_learned' => ['Eloquent'],
            'outputs_links' => [
                ['url' => 'http://example.com', 'description' => 'Project link'],
            ],
            'what_built' => '',
        ],
        'status' => 'pending',
    ]);
});

// decisions_reasoning
it('decisions_reasoning must contain decision_1 and decision_2', function () {
    $this->expectException(\InvalidArgumentException::class);

    WeeklyReports::create([
        'user_id' => $this->user->id,
        'week_start' => now()->startOfWeek(),
        'week_end' => now()->endOfWeek(),
        'journal_number' => 1,
        'entries' => [
            'week_focus' => 'Learning Laravel',
            'topics_learned' => ['Eloquent'],
            'outputs_links' => [
                ['url' => 'http://example.com', 'description' => 'Project link'],
            ],
            'what_built' => 'Weekly journal system',
            'decisions_reasoning' => [
                'decision_1' => 'Use JSON column',
                // missing decision_2
            ],
        ],
        'status' => 'pending',
    ]);
});

// challenges_blockers
it('challenges_blockers cannot be empty', function () {
    $this->expectException(\InvalidArgumentException::class);

    WeeklyReports::create([
        'user_id' => $this->user->id,
        'week_start' => now()->startOfWeek(),
        'week_end' => now()->endOfWeek(),
        'journal_number' => 1,
        'entries' => [
            'week_focus' => 'Learning Laravel',
            'topics_learned' => ['Eloquent'],
            'outputs_links' => [
                ['url' => 'http://example.com', 'description' => 'Project link'],
            ],
            'what_built' => 'Weekly journal system',
            'decisions_reasoning' => [
                'decision_1' => 'Use JSON column',
                'decision_2' => 'Filament form for UI',
            ],
            'challenges_blockers' => '',
        ],
        'status' => 'pending',
    ]);
});

// improve_next_time
it('improve_next_time must contain improvement_1 and improvement_2', function () {
    $this->expectException(\InvalidArgumentException::class);

    WeeklyReports::create([
        'user_id' => $this->user->id,
        'week_start' => now()->startOfWeek(),
        'week_end' => now()->endOfWeek(),
        'journal_number' => 1,
        'entries' => [
            'week_focus' => 'Learning Laravel',
            'topics_learned' => ['Eloquent'],
            'outputs_links' => [
                ['url' => 'http://example.com', 'description' => 'Project link'],
            ],
            'what_built' => 'Weekly journal system',
            'decisions_reasoning' => [
                'decision_1' => 'Use JSON column',
                'decision_2' => 'Filament form for UI',
            ],
            'challenges_blockers' => 'Some blockers',
            'improve_next_time' => [
                'improvement_1' => 'Add more guidance',
                // missing improvement_2
            ],
        ],
        'status' => 'pending',
    ]);
});

// key_takeaway
it('key_takeaway cannot be empty', function () {
    $this->expectException(\InvalidArgumentException::class);

    WeeklyReports::create([
        'user_id' => $this->user->id,
        'week_start' => now()->startOfWeek(),
        'week_end' => now()->endOfWeek(),
        'journal_number' => 1,
        'entries' => [
            'week_focus' => 'Learning Laravel',
            'topics_learned' => ['Eloquent'],
            'outputs_links' => [
                ['url' => 'http://example.com', 'description' => 'Project link'],
            ],
            'what_built' => 'Weekly journal system',
            'decisions_reasoning' => [
                'decision_1' => 'Use JSON column',
                'decision_2' => 'Filament form for UI',
            ],
            'challenges_blockers' => 'Some blockers',
            'improve_next_time' => [
                'improvement_1' => 'Add more guidance',
                'improvement_2' => 'Better validation',
            ],
            'key_takeaway' => '',
        ],
        'status' => 'pending',
    ]);
});
