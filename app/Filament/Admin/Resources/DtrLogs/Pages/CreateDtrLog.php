<?php

namespace App\Filament\Admin\Resources\DtrLogs\Pages;

use App\Filament\Admin\Resources\DtrLogs\DtrLogResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDtrLog extends CreateRecord
{
    protected static string $resource = DtrLogResource::class;
}
