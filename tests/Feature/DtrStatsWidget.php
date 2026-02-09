<?php

use App\Filament\Intern\Resources\DailyTimeRecords\Widgets\DtrStatsWidget;
use App\Models\DtrLog;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('intern'));
});

// Helper to create logs with minutes
function createLog($user, $type, $date, $time, $workMins = 0, $lateMins = 0)
{
    return DtrLog::create([
        'user_id' => $user->id,
        'shift_id' => $user->shift_id,
        'type' => $type,
        'work_date' => $date,
        'recorded_at' => "{$date} {$time}",
        'work_minutes' => $workMins,
        'late_minutes' => $lateMins,
    ]);
}

it('shows correct stats for a perfect 8-hour day', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $today = '2024-02-01';

    // create 4 perfect time logs
    createLog($user, 1, $today, '08:00:00');
    createLog($user, 2, $today, '12:00:00', 240);
    createLog($user, 1, $today, '13:00:00');
    createLog($user, 2, $today, '17:00:00', 240);

    Livewire::test(DtrStatsWidget::class)
        ->assertSee('8h 0m')
        ->assertSee('1')
        ->assertSee('0');
});

it('shows correct stats for a late day', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $today = '2024-02-01';


    // Wcreate for log with late
    createLog($user, 1, $today, '08:10:00', 0, 10);
    createLog($user, 2, $today, '12:00:00', 230);
    createLog($user, 1, $today, '13:20:00', 0, 20);
    createLog($user, 2, $today, '17:00:00', 220);

    Livewire::test(DtrStatsWidget::class)
        ->assertSee('7h 30m')
        ->assertSee('1')
        ->assertSee('30m');
});

it('shows correct stats for an undertime day', function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $today = '2024-02-01';

    // crete 4 lofs with undertime
    createLog($user, 1, $today, '08:00:00');
    createLog($user, 2, $today, '09:00:00', 60);
    createLog($user, 1, $today, '13:00:00');
    createLog($user, 2, $today, '17:00:00', 240);

    Livewire::test(DtrStatsWidget::class)
        ->assertSee('5h 0m')
        ->assertSee('1')
        ->assertSee('0');
});

it('calculates totals across multiple days', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Day 1: 8 hours
    createLog($user, 2, '2024-02-01', '17:00:00', 480);
    // Day 2: 4 hours
    createLog($user, 2, '2024-02-02', '17:00:00', 240);

    Livewire::test(DtrStatsWidget::class)
        ->assertSee('12h 0m')
        ->assertSee('2');
});
