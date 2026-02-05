<?php

use App\Models\User;
use App\Models\Shift;
use App\Models\DtrLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use App\Filament\Intern\Resources\DailyTimeRecords\Pages\ListDailyTimeRecords;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use phpDocumentor\Reflection\Types\This;

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

// Day shift = business date is today
it('uses today for a day shift', function () {

    $shift = createDayShift();
    $user = User::factory()->create(['shift_id' => $shift->id]);
    $this->actingAs($user);

    Carbon::setTestNow('2024-02-01 09:00:00');

    Livewire::test(ListDailyTimeRecords::class)
        ->callAction('time_in');

    $this->assertDatabaseHas('dtr_logs', [
        'user_id' => $user->id,
        'work_date' => '2024-02-01',
    ]);
});

//Night shift = business date is yesterday
it('uses yesterday for night shift before morning', function () {

    $shift = createNightShift();

    $user = User::factory()->create(['shift_id' => $shift->id]);
    $this->actingAs($user);

    Carbon::setTestNow('2024-02-02 02:00:00');

    Livewire::test(ListDailyTimeRecords::class)
        ->callAction('time_in');

    $this->assertDatabaseHas('dtr_logs', [
        'user_id' => $user->id,
        'work_date' => '2024-02-01',
    ]);
});


//allow first time in
it('allows first time in', function () {
    $shift = createDayShift();
    $user = User::factory()->create(['shift_id' => $shift->id]);
    $this->actingAs($user);

    Livewire::test(ListDailyTimeRecords::class)
        ->callAction('time_in');

    $this->assertDatabaseHas('dtr_logs', [
        'user_id' => $user->id,
        'type' => 1
    ]);
});

// allow first OUT after first IN
it('allows first OUT after first IN', function () {

    $shift = createDayShift();
    $user = User::factory()->create(['shift_id' => $shift->id]);
    $this->actingAs($user);

    DtrLog::create([
        'user_id' => $user->id,
        'type' => 1,
        'recorded_at' => now(),
        'work_date' => now()->format('Y-m-d'),
    ]);

    Livewire::test(ListDailyTimeRecords::class)
        ->callAction('time_out');

    $this->assertDatabaseHas('dtr_logs', [
        'user_id' => $user->id,
        'type' => 2,
    ]);
});

//allows second time IN
it('allows second time in', function () {
    $shift = createDayShift();
    $user = User::factory()->create(['shift_id' => $shift->id]);
    $this->actingAs($user);

    DtrLog::create([
        'user_id' => $user->id,
        'type' => 1,
        'recorded_at' => now()->subHours(4),
        'work_date' => now()->format('Y-m-d')
    ]);

    DtrLog::create([
        'user_id' => $user->id,
        'type' => 2,
        'recorded_at' => now()->subHours(3),
        'work_date' => now()->format('Y-m-d')
    ]);

    Livewire::test(ListDailyTimeRecords::class)
        ->callAction('time_in');

    $this->assertDatabaseHas('dtr_logs', [
        'user_id' => $user->id,
        'type' => 1,
    ]);
});

//allows last time out
it('allows last time out', function () {

    $shift = createDayShift();
    $user = User::factory()->create(['shift_id' => $shift->id]);
    $this->actingAs($user);

    DtrLog::create([
        'user_id' => $user->id,
        'type' => 1,
        'recorded_at' => now()->subHours(4),
        'work_date' => now()->format('Y-m-d')
    ]);

    DtrLog::create([
        'user_id' => $user->id,
        'type' => 2,
        'recorded_at' => now()->subHours(3),
        'work_date' => now()->format('Y-m-d')
    ]);

    DtrLog::create([
        'user_id' => $user->id,
        'type' => 1,
        'recorded_at' => now()->subHours(3),
        'work_date' => now()->format('Y-m-d')
    ]);

    Livewire::test(ListDailyTimeRecords::class)
        ->callAction('time_out');

    $this->assertDatabaseHas('dtr_logs', [
        'user_id' => $user->id,
        'type' => 2
    ]);
});


//test to block the action after 2 session
it('disables clocking actions after two sessions are complete', function () {
    $shift = createDayShift();
    $user = User::factory()->create(['shift_id' => $shift->id]);
    $this->actingAs($user);

    $today = now()->format('Y-m-d');

    for ($i = 0; $i < 4; $i++) {
        DtrLog::create([
            'user_id' => $user->id,
            'type' => ($i % 2 == 0) ? 1 : 2,
            'work_date' => $today,
            'recorded_at' => now()->subMinutes(10 - $i),
        ]);
    }

    // Assert that the actions are now disabled
    Livewire::test(ListDailyTimeRecords::class)
        ->assertActionDisabled('time_in')
        ->assertActionDisabled('time_out');
});
