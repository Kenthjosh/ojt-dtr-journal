<?php

use App\Filament\Intern\Resources\DailyTimeRecords\Widgets\DtrStatsWidget;
use App\Models\DtrLog;
use App\Models\Shift;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
// Set the panel context before every test
beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('intern'));
});

//helper functions 
function createDayShift()
{
    return Shift::create([
        'name' => 'Day',
        'session_1_start' => '08:00:00',
        'session_1_end'   => '12:00:00',
        'session_2_start' => '13:00:00',
        'session_2_end'   => '17:00:00',
    ]);
}

function createNightShift()
{
    return Shift::create([
        'name' => 'Night',
        'session_1_start' => '20:00:00',
        'session_1_end'   => '00:00:00',
        'session_2_start' => '01:00:00',
        'session_2_end'   => '05:00:00',
    ]);
}

it('shows correct stats for a perfect 8-hour day', function () {
    $shift = createDayShift();
    $user = User::factory()->create(['shift_id' => $shift->id]);

    $this->actingAs($user);

    $today = '2024-02-01';

    // Morning: 08:00 - 12:00
    DtrLog::create(
        [
            'user_id' => $user->id,
            'type' => 1,
            'work_date' => $today,
            'recorded_at' => "{$today} 08:00:00"
        ]
    );

    DtrLog::create(
        [
            'user_id' => $user->id,
            'type' => 2,
            'work_date' => $today,
            'recorded_at' => "{$today} 12:00:00"
        ]
    );

    // Afternoon: 13:00 - 17:00
    DtrLog::create(
        [
            'user_id' => $user->id,
            'type' => 1,
            'work_date' => $today,
            'recorded_at' => "{$today} 13:00:00"
        ]
    );

    DtrLog::create(
        [
            'user_id' => $user->id,
            'type' => 2,
            'work_date' => $today,
            'recorded_at' => "{$today} 17:00:00"
        ]
    );

    // Test the Widget
    \Livewire\Livewire::test(DtrStatsWidget::class)
        ->assertSee('8h 0m')
        ->assertSee('1')
        ->assertSee('0');
});

it('shows correct stats for a late day', function () {
    $shift = createDayShift();
    $user = User::factory()->create(['shift_id' => $shift->id]);

    $this->actingAs($user);

    $today = '2024-02-01';

    // Morning: 08:00 - 12:00
    DtrLog::create(
        [
            'user_id' => $user->id,
            'type' => 1,
            'work_date' => $today,
            'recorded_at' => "{$today} 08:10:00"
        ]
    );

    DtrLog::create(
        [
            'user_id' => $user->id,
            'type' => 2,
            'work_date' => $today,
            'recorded_at' => "{$today} 12:00:00"
        ]
    );

    // Afternoon: 13:00 - 17:00
    DtrLog::create(
        [
            'user_id' => $user->id,
            'type' => 1,
            'work_date' => $today,
            'recorded_at' => "{$today} 13:20:00"
        ]
    );

    DtrLog::create(
        [
            'user_id' => $user->id,
            'type' => 2,
            'work_date' => $today,
            'recorded_at' => "{$today} 17:00:00"
        ]
    );

    // Test the Widget
    \Livewire\Livewire::test(DtrStatsWidget::class)
        ->assertSee('7h 30m')
        ->assertSee('1')
        ->assertSee('30');
});

it('shows correct stats for a undertime day', function () {
    $shift = createDayShift();
    $user = User::factory()->create(['shift_id' => $shift->id]);

    $this->actingAs($user);

    $today = '2024-02-01';

    // Morning: 08:00 - 12:00
    DtrLog::create(
        [
            'user_id' => $user->id,
            'type' => 1,
            'work_date' => $today,
            'recorded_at' => "{$today} 08:00:00"
        ]
    );

    DtrLog::create(
        [
            'user_id' => $user->id,
            'type' => 2,
            'work_date' => $today,
            'recorded_at' => "{$today} 09:00:00"
        ]
    );

    // Afternoon: 13:00 - 17:00
    DtrLog::create(
        [
            'user_id' => $user->id,
            'type' => 1,
            'work_date' => $today,
            'recorded_at' => "{$today} 13:00:00"
        ]
    );

    DtrLog::create(
        [
            'user_id' => $user->id,
            'type' => 2,
            'work_date' => $today,
            'recorded_at' => "{$today} 17:00:00"
        ]
    );

    // Test the Widget
    \Livewire\Livewire::test(DtrStatsWidget::class)
        ->assertSee('5h 0m')
        ->assertSee('1')
        ->assertSee('0');
});
