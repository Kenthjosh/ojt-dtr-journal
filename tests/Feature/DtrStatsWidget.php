<?php

use App\Filament\Intern\Resources\DailyTimeRecords\Widgets\DtrStatsWidget;
use App\Models\DtrLog;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel("intern"));
});

it("displays correct total hours and late minutes", function () {
    $user = User::factory()->create();
    $this->actingAs($user);
    $now = now();

    DtrLog::create([
        "user_id" => $user->id,
        "type" => 2,
        "work_minutes" => 240,
        "work_date" => "2026-03-14",
        "recorded_at" => $now, // Fixes the error
    ]);

    DtrLog::create([
        "user_id" => $user->id,
        "type" => 1,
        "late_minutes" => 10,
        "work_date" => "2026-03-14",
        "recorded_at" => $now, // Fixes the error
    ]);

    DtrLog::create([
        "user_id" => $user->id,
        "type" => 2,
        "work_minutes" => 480,
        "work_date" => "2026-03-15",
        "recorded_at" => $now, // Fixes the error
    ]);

    Livewire::test(DtrStatsWidget::class)
        ->assertSee("12h 0m")
        ->assertSee("10m")
        ->assertSee("2");
});
