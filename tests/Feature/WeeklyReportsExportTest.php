<?php

use App\Filament\Admin\Resources\WeeklyReports\Pages\ViewWeeklyReports;
use App\Filament\Admin\Resources\WeeklyReports\WeeklyReportsResource;
use App\Models\User;
use App\Models\WeeklyReports;
use App\Models\WorkCategory;
use App\Services\Exports\WeeklyReportsExportService;
//use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use ZipArchive;

//uses(RefreshDatabase::class);

beforeEach(function () {
    Storage::fake("local");
});

it("Exports only certified reports into a zip", function () {
    User::factory()
        ->count(1)
        ->create([
            "role" => "admin",
        ]);


    $certified = WeeklyReports::factory()
        ->count(2)
        ->create([
            "status" => "certified",
                'work_category' => WorkCategory::first()?->id??WorkCategory::factory(),
        ]);

    WeeklyReports::factory()->create([
        "status" => "pending",
        'work_category' => WorkCategory::first()?->id??WorkCategory::factory(),
    ]);

    $service = app(WeeklyReportsExportService::class);

    $path = $service->exportCertifiedReports($certified);

    expect($path)->toBeString();
    expect(file_exists($path))->toBeTrue();

    $zip = new ZipArchive();
    $opened = $zip->open($path);

    expect($opened)->toBeTrue();
    expect($zip->numFiles)->toBe(2);

    $zip->close();
});

it("exports a docx file when one file is exported", function () {
    $admin = User::factory()->create(["role" => "admin"]);
    $this->actingAs($admin);

    $report = WeeklyReports::factory()->create([
        "status" => "certified",
        "certified_by" => $admin->id,
        'work_category' => WorkCategory::first()?->id??WorkCategory::factory(),
    ]);

    $service = app(WeeklyReportsExportService::class);

    $path = $service->exportCertifiedReports(collect([$report]));

    $response = $this->get(
        route("exports.download", ["path" => encrypt($path)]),
    );

    $response->assertOk();
    $response->assertHeader("content-disposition");
});

it("Displays the Export button in the Header Actions when called", function () {
    // Sample Report data with Certifications
    $admin =
        User::where("role", "admin")->first() ??
        User::factory()->create(["role" => "admin"]);

    $this->actingAs($admin);

    $report = WeeklyReports::factory()
        ->count(1)
        ->create([
            "status" => "certified",
            "certified_by" => $admin->id,
        ])
        ->first();

    // Mount the Simulation
    $response = $this->get(
        WeeklyReportsResource::getUrl("view", ["record" => $report->id]),
    );
    $response->assertSee("Export");
});

it("disables the Export Button when the report is not certified", function () {
    $admin =
        User::where("role", "admin")->first() ??
        User::factory()->create(["role" => "admin"]);

    $this->actingAs($admin);

    $report = WeeklyReports::factory()->create([
        "status" => "pending",
        "certified_by" => null,
    ]);

    Livewire::test(ViewWeeklyReports::class, [
        "record" => $report->id,
    ])->assertSee("Cannot export when not certified");
});
