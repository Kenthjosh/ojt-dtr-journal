<?php

use App\Filament\Admin\Resources\DailyTimeRecords\Pages\ListDailyTimeRecords;
use App\Filament\Exports\DailyTimeRecordsExporter;
use App\Models\DtrLog;
use App\Models\User;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('creates an export record for daily time records', function () {

    $user = User::factory()->create();

    $this->actingAs($user);
    DtrLog::create(attributes: [
        'user_id' => $user->id,
        'work_date' => now(),
        'recorded_at' => now(),
        'type' => 1,
    ]);

    Export::query()->delete();

    Export::create([
        'exporter' => DailyTimeRecordsExporter::class,
        'user_id' => $user->id,
        'successful_rows' => 1,
        'total_rows' => 1,
        'file_disk' => 'local',
        'file_name' => 'test.xlsx',
    ]);

    expect(Export::count())->toBe(1);
});

it('displays an export bulk action in the daily time records table (Admin Panel)', function () {

    $user = User::factory()->create([
        'role' => 'admin',
    ]);

    $this->actingAs($user);

    Livewire::test(ListDailyTimeRecords::class)
        ->callTableBulkAction(ExportBulkAction::class, ['export', 1, 2, 3]);
});

it('displays an export bulk action in the daily time records table (Intern Panel)', function () {

    $user = User::factory()->create([
        'role' => 'intern',
    ]);

    $this->actingAs($user);

    Livewire::test(ListDailyTimeRecords::class)
        ->callTableBulkAction(ExportBulkAction::class, ['export', 1, 2, 3]);
});
