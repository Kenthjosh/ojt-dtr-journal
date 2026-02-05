<?php

namespace App\Filament\Intern\Resources\WeeklyReports\Pages;

use App\Filament\Intern\Resources\WeeklyReports\WeeklyReportsResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateWeeklyReports extends CreateRecord
{
    protected static string $resource = WeeklyReportsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = Auth::id();
        $data['status'] = $data['status'] ?? 'pending';

        return $data;
    }

    public function canCreateAnother(): bool
    {
        return false;
    }
}
