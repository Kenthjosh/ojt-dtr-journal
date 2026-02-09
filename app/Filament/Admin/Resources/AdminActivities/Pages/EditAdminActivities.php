<?php

namespace App\Filament\Admin\Resources\AdminActivities\Pages;

use App\Filament\Admin\Resources\AdminActivities\AdminActivitiesResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAdminActivities extends EditRecord
{
    protected static string $resource = AdminActivitiesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
