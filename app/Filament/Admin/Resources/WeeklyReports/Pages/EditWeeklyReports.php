<?php

namespace App\Filament\Admin\Resources\WeeklyReports\Pages;

use App\Filament\Admin\Resources\WeeklyReports\WeeklyReportsResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWeeklyReports extends EditRecord
{
    protected static string $resource = WeeklyReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
