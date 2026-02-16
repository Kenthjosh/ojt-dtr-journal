<?php

namespace App\Filament\Intern\Resources\WeeklyReports\Pages;

use App\Filament\Intern\Resources\WeeklyReports\WeeklyReportsResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Filament\Notifications\Notification as FilamentNotification;
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

    // Runs AFTER the record is created
    protected function afterCreate(): void
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            FilamentNotification::make()
                ->title('Weekly Report Submitted!')
                ->body('A new weekly report has been submitted by ' . optional($this->record->user)->name)
                ->success()
                ->sendToDatabase($admin);
        }

        // Optional: intern toast
        FilamentNotification::make()
            ->title('Report Submitted!')
            ->body('All admins have been notified.')
            ->success()
            ->send();
    }
}