<?php

use App\Filament\Resources\WeeklyReports\WeeklyReportsResource;
use App\Models\WeeklyReports;
use App\Models\User;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use App\Services\Exports\WeeklyReportsExportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
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

    $zip = new ZipArchive();
    $opened = $zip->open($zipPath);

    expect($opened)->toBeTrue();
    expect($zip->numFiles)->toBe(2);

    $zip->close();
});


it('Displays the Export button in the Header Actions when called', function() {
    //Sample Report data with Certifications
    $admin = User::where('role', 'admin')->first() ?? User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin);

    $report = WeeklyReports::factory()->count(1)->create([
        'status' => 'certified',
        'certified_by' =>$admin->id, 
    ])->first();
    

    //Mount the Simulation
    $response = $this->get(WeeklyReportsResource::getUrl('view',['record' => $report->id]));
    $response->assertSee('Export');
});
