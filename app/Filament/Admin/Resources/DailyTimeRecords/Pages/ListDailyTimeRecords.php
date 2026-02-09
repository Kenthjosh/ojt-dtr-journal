<?php

namespace App\Filament\Admin\Resources\DailyTimeRecords\Pages;

use App\Filament\Admin\Resources\DailyTimeRecords\DailyTimeRecordsResource;
use Filament\Resources\Pages\ListRecords;

class ListDailyTimeRecords extends ListRecords
{
    protected static string $resource = DailyTimeRecordsResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
