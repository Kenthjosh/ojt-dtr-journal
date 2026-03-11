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

    protected function getLogCount(): int
    {
        $user = Auth::user();
        if (!$user) return 2;

        return DtrLog::where('user_id', $user->id)
            ->where('work_date', $this->getBusinessDate())
            ->count();
    }

    protected function getHeaderActions(): array
    {
        $count = $this->getLogCount();

        return [
            Action::make('time_in')
                ->label('Time In')
                ->color('success')
                ->requiresConfirmation()
                ->disabled($count !== 0)
                ->action(function () {
                    $this->saveLog(1);
                    $this->dispatch('refreshWidgets');
                }),

            Action::make('time_out')
                ->label('Time Out')
                ->color('info')
                ->requiresConfirmation()
                ->disabled($count !== 1)
                ->action(function () {
                    $this->saveLog(2);
                    $this->dispatch('refreshWidgets');
                }),
        ];
    }

    protected function saveLog(int $type): void
    {
        $user = Auth::user();
        $shift = $user->shift;

        $now = Carbon::now();

        $workDate = $this->getBusinessDate();

        $lateMinutes = 0;
        $workMinutes = 0;

        // Use the new clean column names
        $start = Carbon::parse($workDate . ' ' . $shift->start_time);
        $end = Carbon::parse($workDate . ' ' . $shift->end_time);
        $breakStart = Carbon::parse($workDate . ' ' . $shift->break_start);
        $breakEnd = Carbon::parse($workDate . ' ' . $shift->break_end);

        if ($type === 1) {
            if ($now->gt($start)) {
                // Use the absolute flag (true) to ensure the number is positive
                $lateMinutes = $now->diffInMinutes($start, true);
            }
        } else {
            $lastIn = DtrLog::where('user_id', $user->id)
                ->where('work_date', $workDate)
                ->where('type', 1)
                ->latest()
                ->first();

            if ($lastIn) {
                $actualIn = Carbon::parse($lastIn->recorded_at);
                $actualOut = $now;

                // Cap to shift boundaries
                $effectiveIn = $actualIn->lt($start) ? $start : $actualIn;
                $effectiveOut = $actualOut->gt($end) ? $end : $actualOut;

                if ($effectiveOut->gt($effectiveIn)) {
                    // Use 'true' for absolute difference
                    $totalMins = $effectiveOut->diffInMinutes($effectiveIn, true);

                    $overlapStart = $effectiveIn->gt($breakStart) ? $effectiveIn : $breakStart;
                    $overlapEnd = $effectiveOut->lt($breakEnd) ? $effectiveOut : $breakEnd;

                    $overlapMins = 0;
                    if ($overlapEnd->gt($overlapStart)) {
                        // Use 'true' for absolute difference
                        $overlapMins = $overlapEnd->diffInMinutes($overlapStart, true);
                    }

                    // Ensure the result is never below 0
                    $workMinutes = max(0, $totalMins - $overlapMins);
                }
            }
        }

        // Save the log
        DtrLog::create([
            'user_id' => $user->id,
            'shift_id' => $user->shift_id,
            'type' => $type,
            'recorded_at' => $now,
            'work_date' => $workDate,
            'late_minutes' => $lateMinutes,
            'work_minutes' => $workMinutes,
        ]);
    }

    protected function getHeaderWidgets(): array
    {
        return [DtrStatsWidget::class];
    }
}
