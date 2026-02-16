<?php

namespace App\Filament\Admin\Resources\WorkCategories\Pages;

use App\Filament\Admin\Resources\WorkCategories\WorkCategoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWorkCategory extends ViewRecord
{
    protected static string $resource = WorkCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
