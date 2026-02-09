<?php

use App\Filament\Admin\Resources\WeeklyReports\Pages\ViewWeeklyReports;
use App\Models\WeeklyReports;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('certify button is disabled if report is already certified', function () {

    $report = WeeklyReports::factory()->create([
        'certified_at' => now(),
    ]);

    Livewire::test(ViewWeeklyReports::class, ['record' => $report->id])
        ->assertActionDisabled('mark_viewed'); // ✅ Works

});
