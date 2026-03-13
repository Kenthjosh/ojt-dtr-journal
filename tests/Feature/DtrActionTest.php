<?php

use App\Filament\Intern\Resources\DailyTimeRecords\Pages\ListDailyTimeRecords;
use App\Models\DtrLog;
use App\Models\Shift;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use Livewire\Livewire;

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel("intern"));
});

function createDayShift()
{
    return Shift::create([
        "name" => "Day Shift",
        "start_time" => "08:00:00",
        "end_time" => "17:00:00",
        "break_start" => "12:00:00",
        "break_end" => "13:00:00",
    ]);
}

it("alternates buttons for multiple sessions", function () {
    $shift = createDayShift();
    $user = User::factory()->create(["shift_id" => $shift->id]);
    $this->actingAs($user);
    $today = "2026-03-14";

    Carbon::setTestNow("$today 08:00:00");

    // 1. Initial: Time In enabled
    Livewire::test(ListDailyTimeRecords::class)
        ->assertActionEnabled("time_in")
        ->assertActionDisabled("time_out")
        ->callAction("time_in");

    // 2. Clocked In: Time In disabled, Time Out enabled
    Livewire::test(ListDailyTimeRecords::class)
        ->assertActionDisabled("time_in")
        ->assertActionEnabled("time_out")
        ->callAction("time_out");

    // 3. Flexi-Time check: Time In should be enabled AGAIN for a second session
    Livewire::test(ListDailyTimeRecords::class)
        ->assertActionEnabled("time_in")
        ->assertActionDisabled("time_out");
});

it("calculates exact work minutes without capping or break deduction", function () {
    $shift = createDayShift();
    $user = User::factory()->create(["shift_id" => $shift->id]);
    $this->actingAs($user);
    $today = "2026-03-14";

    // Scenario: Clock in at 12:50 PM
    DtrLog::create([
        "user_id" => $user->id,
        "type" => 1, // Becomes "Time In" via cast
        "recorded_at" => "$today 12:50:00",
        "work_date" => $today,
        "shift_id" => $shift->id,
    ]);

    // Clock out at 2:50 PM (Exactly 120 minutes)
    Carbon::setTestNow("$today 14:50:00");

    Livewire::test(ListDailyTimeRecords::class)->callAction("time_out");

    // We check the raw database value for work_minutes
    $log = DtrLog::where('user_id', $user->id)->where('type', 2)->first();
    expect($log->work_minutes)->toBe(120);
});
