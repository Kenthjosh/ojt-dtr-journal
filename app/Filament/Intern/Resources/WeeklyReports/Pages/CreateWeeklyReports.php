<?php

namespace App\Filament\Intern\Resources\WeeklyReports\Pages;

use App\Filament\Intern\Resources\WeeklyReports\WeeklyReportsResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\WeeklyReportSubmitted; // Make sure you have this notification

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

    // This runs AFTER the record is created
    protected function afterCreate(): void
    {
        // Get the admin users (you can filter by role if you have one)
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new WeeklyReportSubmitted($this->record));
        }
    }
}
