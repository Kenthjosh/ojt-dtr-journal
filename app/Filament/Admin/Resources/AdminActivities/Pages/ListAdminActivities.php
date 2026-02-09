<?php

namespace App\Filament\Admin\Resources\AdminActivities\Pages;

use App\Filament\Admin\Resources\AdminActivities\AdminActivitiesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdminActivities extends ListRecords
{
    protected static string $resource = AdminActivitiesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(),
        ];
    }
}
