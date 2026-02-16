<?php

namespace App\Filament\Admin\Resources\WorkCategories\Pages;

use App\Filament\Admin\Resources\WorkCategories\WorkCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWorkCategory extends EditRecord
{
    protected static string $resource = WorkCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
