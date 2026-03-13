<?php

namespace App\Filament\Intern\Resources\DailyTimeRecords\Pages;

use App\Filament\Intern\Resources\DailyTimeRecords\DailyTimeRecordResource;
use App\Filament\Intern\Resources\DailyTimeRecords\Widgets\DtrStatsWidget;
use App\Models\DtrLog;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ListDailyTimeRecords extends ListRecords
{
    protected static string $resource = DailyTimeRecordResource::class;

    protected function getBusinessDate(): string
    {
        $now = Carbon::now();

        // Since shifts start at 4am or 8am, anything after 2am is "Today"
        if ($now->hour < 2) {
            return $now->subDay()->format('Y-m-d');
        }
        return $now->format('Y-m-d');
    }

    protected function getHeaderActions(): array
    {
        // Get the absolute latest log for this business date
        $lastLog = DtrLog::where('user_id', Auth::id())
            ->where('work_date', $this->getBusinessDate())
            ->latest('id')
            ->first();

        $isClockedIn = $lastLog && $lastLog->type === 'Time In';

        return [
            Action::make('time_in')
                ->label('Time In')
                ->color('success')
                ->requiresConfirmation()
                ->disabled($isClockedIn)
                ->action(function () {
                    $this->saveLog(1);
                    $this->dispatch('refreshWidgets');
                }),

            Action::make('time_out')
                ->label('Time Out')
                ->color('info')
                ->requiresConfirmation()
                ->disabled(!$isClockedIn)
                ->action(function () {
                    $this->saveLog(2);
                    $this->dispatch('refreshWidgets');
                }),
        ];
    }
    protected function saveLog(int $type): void
    {
        $user = Auth::user();
        $now = Carbon::now();
        $workDate = $this->getBusinessDate();
        $workMinutes = 0;

        // calculate work minutes on "Time Out"
        if ($type === 2) {
            $lastIn = DtrLog::where('user_id', $user->id)
                ->where('work_date', $workDate)
                ->where('type', 1)
                ->latest('id')
                ->first();

            if ($lastIn) {
                // Calculate raw minutes between this Time Out and the previous Time In
                $workMinutes = Carbon::parse($lastIn->recorded_at)->diffInMinutes($now, true);
            }
        }

        DtrLog::create([
            'user_id' => $user->id,
            'shift_id' => $user->shift_id,
            'type' => $type,
            'recorded_at' => $now,
            'work_date' => $workDate,
            'late_minutes' => 0,
            'work_minutes' => $workMinutes,
        ]);
    }

    protected function getHeaderWidgets(): array
    {
        return [DtrStatsWidget::class];
    }
}
