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

// Helper: Updated to use new column names
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

function createNightShift()
{
    return Shift::create([
        "name" => "Night Shift",
        "start_time" => "20:00:00",
        "end_time" => "05:00:00",
        "break_start" => "00:00:00",
        "break_end" => "01:00:00",
    ]);
}

it("uses today for a day shift", function () {
    $shift = createDayShift();
    $user = User::factory()->create(["shift_id" => $shift->id]);
    $this->actingAs($user);

    Carbon::setTestNow("2026-03-14 09:00:00");

    Livewire::test(ListDailyTimeRecords::class)->callAction("time_in");

    $this->assertDatabaseHas("dtr_logs", [
        "user_id" => $user->id,
        "work_date" => "2026-03-14",
    ]);
});

it("uses yesterday for night shift before 2am cutoff", function () {
    $shift = createNightShift();
    $user = User::factory()->create(["shift_id" => $shift->id]);
    $this->actingAs($user);

    // It's 1:30 AM on the 15th, but it belongs to the 14th business day
    Carbon::setTestNow("2026-03-15 01:30:00");

    Livewire::test(ListDailyTimeRecords::class)->callAction("time_in");

    $this->assertDatabaseHas("dtr_logs", [
        "user_id" => $user->id,
        "work_date" => "2026-03-14",
    ]);
});

it("allows only one session (In and Out) per day", function () {
    $shift = createDayShift();
    $user = User::factory()->create(["shift_id" => $shift->id]);
    $this->actingAs($user);
    $today = "2026-03-14";

    Carbon::setTestNow("$today 08:00:00");

    // 1. Initial State: Time In should be enabled
    Livewire::test(ListDailyTimeRecords::class)
        ->assertActionEnabled("time_in")
        ->callAction("time_in");

    // 2. Middle State: Time In should be disabled, Time Out enabled
    // We start a NEW test instance to ensure the component re-runs getLogCount()
    Livewire::test(ListDailyTimeRecords::class)
        ->assertActionDisabled("time_in")
        ->assertActionEnabled("time_out")
        ->callAction("time_out");

    // 3. Final State: Both should be disabled
    Livewire::test(ListDailyTimeRecords::class)
        ->assertActionDisabled("time_in")
        ->assertActionDisabled("time_out");

    $this->assertEquals(2, DtrLog::where('user_id', $user->id)->where('work_date', $today)->count());
});

it("correctly calculates work minutes and break overlap", function () {
    $shift = createDayShift();
    $user = User::factory()->create(["shift_id" => $shift->id]);
    $this->actingAs($user);
    $today = "2026-03-14";

    // Scenario: Clock in at 12:50 PM, Out at 5:00 PM
    DtrLog::create([
        "user_id" => $user->id,
        "type" => 1,
        "recorded_at" => "$today 12:50:00",
        "work_date" => $today,
        "shift_id" => $shift->id,
    ]);

    Carbon::setTestNow("$today 17:00:00");

    Livewire::test(ListDailyTimeRecords::class)->callAction("time_out");

    // Should be exactly 240 minutes (4 hours)
    $this->assertDatabaseHas("dtr_logs", [
        "user_id" => $user->id,
        "type" => 2,
        "work_minutes" => 240,
    ]);
});
