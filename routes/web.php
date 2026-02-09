<?php

use Filament\Facades\Filament;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(Filament::getPanel('intern')->getUrl());
});

Route::get('/exports/download/{path}', function ($path) {
    $path = decrypt($path);

    abort_unless(file_exists($path), 404);

    return response()
        ->download($path)
        ->deleteFileAfterSend(true);
})->name('exports.download');