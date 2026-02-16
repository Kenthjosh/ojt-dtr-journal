<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

Route::get("/", function () {
    return redirect(Filament::getPanel("intern")->getUrl());
});

Route::get("/exports/download/{path}", function ($path) {
    $path = decrypt($path);

    abort_unless(file_exists($path), 404);

    return response()->download($path)->deleteFileAfterSend(true);
})->name("exports.download");

Route::get("/docs", function () {
    return File::get(public_path("docs/index.html"));
});
