<?php

use App\Filament\Intern\Resources\DailyTimeRecords\Widgets\DtrStatsWidget;
use App\Models\DtrLog;
use App\Models\User;
use Livewire\Livewire;

it("correctly calculates remaining hours for the 729h target", function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Create 10 hours of work (600 minutes)
    DtrLog::create([
        "user_id" => $user->id,
        "work_minutes" => 600,
        "type" => 2,
        "work_date" => now()->format('Y-m-d'),
        "recorded_at" => now(),
    ]);

    $targetMinutes = 729 * 60; // 43,740
    $expectedRemainingMinutes = $targetMinutes - 600;

    // Convert minutes to the format "719h 0m" (43140 / 60)
    $expectedLabel = "719h 0m";

    Livewire::test(DtrStatsWidget::class)
        ->assertSee("10h 0m") // Total Rendered
        ->assertSee($expectedLabel); // Remaining
});
