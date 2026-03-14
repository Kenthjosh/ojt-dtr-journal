<?php

namespace App\Filament\Admin\Resources\DtrLogs\Pages;

use App\Filament\Admin\Resources\DtrLogs\DtrLogResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewDtrLog extends ViewRecord
{
    protected static string $resource = DtrLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
