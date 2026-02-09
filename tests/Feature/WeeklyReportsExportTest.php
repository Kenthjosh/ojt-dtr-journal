<?php

use App\Filament\Admin\Resources\WeeklyReports\Pages\ViewWeeklyReports;
use App\Filament\Admin\Resources\WeeklyReports\WeeklyReportsResource;
use App\Models\User;
use App\Models\WeeklyReports;
use App\Services\Exports\WeeklyReportsExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake('local');
});

it('Exports only certified reports into a zip', function () {

    User::factory()->count(1)->create([
        'role' => 'admin',
    ]);

    $certified = WeeklyReports::factory()->count(2)->create([
        'status' => 'certified',
    ]);

    WeeklyReports::factory()->create([
        'status' => 'pending',
    ]);

    $service = app(WeeklyReportsExportService::class);

    $response = $service->exportCertifiedReports($certified);

    expect($response)->toBeInstanceOf(BinaryFileResponse::class);

    $zipPath = $response->getFile()->getPathname();

    expect(file_exists($zipPath))->toBeTrue();

    $zip = new ZipArchive;
    $opened = $zip->open($zipPath);

    expect($opened)->toBeTrue();
    expect($zip->numFiles)->toBe(2);

    $zip->close();
});

it('exports a docx file when one file is exported', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $report = WeeklyReports::factory()->create([
        'status' => 'certified',
        'certified_by' => $admin->id,
    ]);

    $service = app(WeeklyReportsExportService::class);

    $response = $service->exportCertifiedReports(collect([$report]));

    expect($response)->toBeInstanceOf(BinaryFileResponse::class);

    $filePath = $response->getFile()->getPathname();
    expect(file_exists($filePath))->toBeTrue();
    expect(pathinfo($filePath, PATHINFO_EXTENSION))->toBe('docx');
});

it('Displays the Export button in the Header Actions when called', function () {
    // Sample Report data with Certifications
    $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin);

    $report = WeeklyReports::factory()->count(1)->create([
        'status' => 'certified',
        'certified_by' => $admin->id,
    ])->first();

    // Mount the Simulation
    $response = $this->get(WeeklyReportsResource::getUrl('view', ['record' => $report->id]));
    $response->assertSee('Export');
});

it('disables the Export Button when the report is not certified', function () {
    $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin);

    $report = WeeklyReports::factory()->create([
        'status' => 'pending',
        'certified_by' => null,
    ]);

    Livewire::test(ViewWeeklyReports::class, ['record' => $report->id])
        ->assertSee('Cannot export when not certified');
});
