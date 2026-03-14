<?php

namespace App\Filament\Admin\Resources\DtrLogs\Pages;

use App\Filament\Admin\Resources\DtrLogs\DtrLogResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDtrLog extends EditRecord
{
    protected static string $resource = DtrLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
