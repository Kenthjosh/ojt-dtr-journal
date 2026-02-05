<?php

namespace App\Filament\Intern\Resources\DailyTimeRecords\Pages;

use App\Filament\Intern\Resources\DailyTimeRecords\DailyTimeRecordResource;
use App\Filament\Intern\Resources\DailyTimeRecords\Widgets\DtrStatsWidget;
use Filament\Resources\Pages\ListRecords;
use App\Models\DtrLog;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Carbon;
use \App\Models\User;

use function Symfony\Component\Clock\now;

class ListDailyTimeRecords extends ListRecords
{
    protected static string $resource = DailyTimeRecordResource::class;

    //function to get the date of the started shift
    protected function getBusinessDate(): string
    {
        $user = Auth::user();
        $now = Carbon::now();
        $shift = $user->shift;

        if ($shift) {
            // Night shift check
            $isNightShift = $shift->session_2_end < $shift->session_1_start;

            // If it's night shift
            if ($isNightShift && $now->hour < 10) {
                return $now->subDay()->format('Y-m-d');
            }
        }

        return $now->format('Y-m-d');
    }

    //function to count how many logs exist for this specific busines date
    protected function getLogCount(): int
    {
        return DtrLog::where('user_id', Auth::id())
            ->where('work_date', $this->getBusinessDate())
            ->count();
    }

    //function for the action buttons
    protected function getHeaderActions(): array
    {
        $logCount = $this->getLogCount();

        return [
            // For time in
            Action::make('time_in')
                ->Label('Time In')
                ->color('success')
                ->requiresConfirmation()
                ->disabled(!($logCount === 0 || $logCount === 2))
                ->action(fn() => $this->saveLog(1))
                ->successNotificationTitle('Clocked in successfully'),

            //For time out
            Action::make('time_out')
                ->Label('Time Out')
                ->color('info')
                ->requiresConfirmation()
                ->disabled(!($logCount === 1 || $logCount === 3))
                ->action(fn() => $this->saveLog(2))
                ->successNotificationTitle('Clocked out successfully')
        ];
    }

    // function to save the log to database
    protected function saveLog(int $type): void
    {
        $user = Auth::user();

        DtrLog::create([
            'user_id' => Auth::id(),
            'shift_id' => $user->shift_id,
            'type' => $type,
            'recorded_at' => now(),
            'work_date' => $this->getBusinessDate(),
        ]);
    }

    //function to display the widgets
    protected function getHeaderWidgets(): array
    {
        return [
            DtrStatsWidget::class
        ];
    }
}
