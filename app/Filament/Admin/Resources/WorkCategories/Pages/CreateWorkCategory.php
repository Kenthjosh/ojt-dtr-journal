<?php

namespace App\Filament\Admin\Resources\WorkCategories\Pages;

use App\Filament\Admin\Resources\WorkCategories\WorkCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWorkCategory extends CreateRecord
{
    protected static string $resource = WorkCategoryResource::class;
}
