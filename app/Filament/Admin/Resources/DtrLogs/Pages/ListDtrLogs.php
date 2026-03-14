<?php

namespace App\Filament\Admin\Resources\DtrLogs\Pages;

use App\Filament\Admin\Resources\DtrLogs\DtrLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDtrLogs extends ListRecords
{
    protected static string $resource = DtrLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
