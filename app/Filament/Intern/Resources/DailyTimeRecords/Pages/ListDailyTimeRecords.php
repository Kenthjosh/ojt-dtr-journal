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

    // function to get the date of the started shift
    protected function getBusinessDate(): string
    {
        $user = Auth::user();
        $now = Carbon::now();
        if (! $user || ! $user->shift) {
            return $now->format('Y-m-d');
        }

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

    // function to count how many logs exist for this specific busines date
    protected function getLogCount(): int
    {
        $user = Auth::user();
        if (! $user || ! $user->shift) {
            return 4;
        }

        $shift = $user->shift;
        $now = Carbon::now();
        $workDate = $this->getBusinessDate();

        $s2End = Carbon::parse($workDate . ' ' . $shift->session_2_end);

        // Handle night shift
        $s1Start = Carbon::parse($workDate . ' ' . $shift->session_1_start);
        if ($s2End->lt($s1Start)) {
            $s2End->addDay();
        }

        // Get the current logs for the day
        $logs = DtrLog::where('user_id', $user->id)
            ->where('work_date', $workDate)
            ->orderBy('recorded_at', 'asc')
            ->get();

        $logCount = $logs->count();
        $lastLog = $logs->last();

        // disable buttons after session 2 end, but remain enable if the user didn't time out yet
        if ($now->gte($s2End)) {
            if ($logCount === 0 || ($lastLog && $lastLog->type === 2)) {
                return 4;
            }
        }

        // disable button after 4 logs
        return ($logCount <= 3) ? $logCount : 4;
    }

    // function for the action buttons
    protected function getHeaderActions(): array
    {
        $logCount = $this->getLogCount();

        return [
            // For time in
            Action::make('time_in')
                ->Label('Time In')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    $this->saveLog(1);
                    $this->dispatch('refreshWidgets');
                    $this->dispatch('$refresh');
                })
                ->disabled(fn() => ! ($this->getLogCount() === 0 || $this->getLogCount() === 2))
                ->successNotificationTitle('Clocked in successfully'),

            // For time out
            Action::make('time_out')
                ->Label('Time Out')
                ->color('info')
                ->requiresConfirmation()
                ->action(function () {
                    $this->saveLog(2);
                    $this->dispatch('refreshWidgets');
                    $this->dispatch('$refresh');
                })
                ->disabled(fn() => ! ($this->getLogCount() === 1 || $this->getLogCount() === 3))
                ->successNotificationTitle('Clocked out successfully'),
        ];
    }

    // function to save the log to database
    protected function saveLog(int $type): void
    {
        $user = Auth::user();
        $shift = $user->shift;
        $now = Carbon::now();
        $workDate = $this->getBusinessDate();

        $lateMinutes = 0;
        $workMinutes = 0;

        // Standardize Session boundaries for the current Business Date
        $s1Start = Carbon::parse($workDate . ' ' . $shift->session_1_start);
        $s1End = Carbon::parse($workDate . ' ' . $shift->session_1_end);
        $s2Start = Carbon::parse($workDate . ' ' . $shift->session_2_start);
        $s2End = Carbon::parse($workDate . ' ' . $shift->session_2_end);

        // Handle Night Shift rollovers for boundaries
        if ($s1End->lt($s1Start)) {
            $s1End->addDay();
        }
        while ($s2Start->lt($s1End)) {
            $s2Start->addDay();
        }
        while ($s2End->lt($s2Start)) {
            $s2End->addDay();
        }

        // time in
        if ($type === 1) {

            // Calculate the "Switch Point" on which session the logs belongs
            $gapMinutes = $s1End->diffInMinutes($s2Start);
            $switchPoint = $s1End->copy()->addMinutes($gapMinutes / 2);

            // If current time is past the midpoint, logs belongs to sessio; otherwise Session 1
            $targetStart = ($now->gt($switchPoint)) ? $s2Start : $s1Start;

            if ($now->gt($targetStart)) {
                $lateMinutes = $now->diffInMinutes($targetStart);
            }

            // time out
        } elseif ($type === 2) {
            $lastIn = DtrLog::where('user_id', $user->id)
                ->where('work_date', $workDate)
                ->where('type', 1)
                ->latest('recorded_at')
                ->first();

            if ($lastIn) {

                $actualIn = Carbon::parse($lastIn->recorded_at);

                // Calculate the "Switch Point" on which session the logs belongs
                $gapMinutes = $s1End->diffInMinutes($s2Start);
                $switchPoint = $s1End->copy()->addMinutes($gapMinutes / 2);

                $officialEnd = ($actualIn->gt($switchPoint)) ? $s2End : $s1End;

                $effectiveOut = $now->gt($officialEnd) ? $officialEnd : $now;

                $workMinutes = max(0, $actualIn->diffInMinutes($effectiveOut, false));
            }
        }

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

    // function to display the widgets
    protected function getHeaderWidgets(): array
    {
        return [
            DtrStatsWidget::class,
        ];
    }
}
