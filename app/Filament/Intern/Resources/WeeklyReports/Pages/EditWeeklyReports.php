<?php

namespace App\Filament\Intern\Resources\WeeklyReports\Pages;

use App\Filament\Intern\Resources\WeeklyReports\WeeklyReportsResource;
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
            DeleteAction::make()
                ->hidden(fn (): bool => $this->record->status === 'certified'),
        ];
    }
}
