<?php

namespace App\Filament\Admin\Resources\DailyTimeRecords\Pages;

use App\Filament\Admin\Resources\DailyTimeRecords\DailyTimeRecordsResource;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\Url;

class ListDailyTimeRecords extends ListRecords
{
    protected static string $resource = DailyTimeRecordsResource::class;
    protected function getTableQueryString(): array
    {
        return [
            'tableSearch' => ['except' => ''],
        ];
    }
    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
