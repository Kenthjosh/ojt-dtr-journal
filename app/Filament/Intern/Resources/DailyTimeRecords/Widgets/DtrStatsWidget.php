<?php

namespace App\Filament\Intern\Resources\DailyTimeRecords\Widgets;

use App\Models\DtrLog;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DtrStatsWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();

        if (! $shift) {
            return [];
        }

        // Count unique work dates to get total days worked
        $totalDays = DtrLog::where('user_id', $user->id)
            ->distinct('work_date')
            ->count('work_date');

        return [
            Stat::make('Total Hours', $this->formatTime($stats->total_work ?? 0))
                ->description('Credited work time')
                ->color('success'),

            Stat::make('Total Days', $totalDays)
                ->description('Days with recorded logs'),

            Stat::make('Overall Late', $this->formatTime($stats->total_late ?? 0))
                ->description('Total tardiness recorded')
                ->color('danger'),
        ];
    }

    // function to format time
    private function formatTime(int $totalMinutes): string
    {
        if ($totalMinutes <= 0) {
            return '0';
        }

        $hours = floor($totalMinutes / 60);
        $mins = $totalMinutes % 60;

        if ($hours > 0) {
            return "{$hours}h {$mins}m";
        }

        return "{$mins}m";
    }

    // function to calculate total hours for widgets
    protected function calculateTotalHours($logsByDay, $shift): string
    {
        $totalMinutes = 0;

        foreach ($logsByDay as $workDate => $logs) {
            $totalMinutes += $this->getDayWorkMinutes($workDate, $logs, $shift);
        }

        return $this->formatTime($totalMinutes);
    }

    // function to calculate total days for widgets
    protected function calculateTotalDays($logsByDay): int
    {
        $days = 0;

        foreach ($logsByDay as $workDate => $logs) {

            // Count the day as long as there is at least one "In" and one "Out" (2 logs)
            if ($logs->count() >= 2) {
                $days++;
            }
        }

        return $days;
    }

    // funciton to get the total lates for widgets
    protected function calculateTotalLates($logsByDay, $shift): string
    {
        $totalLateMinutes = 0;

        foreach ($logsByDay as $workDate => $logs) {
            $sorted = $logs->sortBy('recorded_at')->values();

            // Session 1 Late Check
            if (isset($sorted[0])) {
                $schedIn1 = Carbon::parse($workDate.' '.$shift->session_1_start);
                $actualIn1 = Carbon::parse($sorted[0]->recorded_at);

                if ($actualIn1->gt($schedIn1)) {
                    $totalLateMinutes += $actualIn1->diffInMinutes($schedIn1, true);
                }
            }

            // Session 2 Late Check
            if (isset($sorted[2])) {
                $schedIn2 = Carbon::parse($workDate.' '.$shift->session_2_start);

                // Night shift crossover check
                if ($schedIn2->lt(Carbon::parse($workDate.' '.$shift->session_1_start))) {
                    $schedIn2->addDay();
                }

                $actualIn2 = Carbon::parse($sorted[2]->recorded_at);
                if ($actualIn2->gt($schedIn2)) {
                    $totalLateMinutes += $actualIn2->diffInMinutes($schedIn2, true);
                }
            }
        }

        return $this->formatTime($totalLateMinutes);
    }

    // function to get the Minutes of work
    private function getDayWorkMinutes($workDate, $logs, $shift): int
    {
        $minutes = 0;
        $sorted = $logs->sortBy('recorded_at')->values();

        // Session 1
        if (isset($sorted[0]) && isset($sorted[1])) {
            $s1Start = Carbon::parse($workDate.' '.$shift->session_1_start);
            $s1End = Carbon::parse($workDate.' '.$shift->session_1_end);
            if ($s1End->lt($s1Start)) {
                $s1End->addDay();
            }

            $actIn = Carbon::parse($sorted[0]->recorded_at);
            $actOut = Carbon::parse($sorted[1]->recorded_at);

            $start = $actIn->gt($s1Start) ? $actIn : $s1Start;
            $end = $actOut->lt($s1End) ? $actOut : $s1End;

            if ($end->gt($start)) {
                $minutes += $start->diffInMinutes($end, true);
            }
        }

        // Session 2
        if (isset($sorted[2]) && isset($sorted[3])) {
            $s2Start = Carbon::parse($workDate.' '.$shift->session_2_start);
            $s2End = Carbon::parse($workDate.' '.$shift->session_2_end);

            if ($s2Start->lt(Carbon::parse($workDate.' '.$shift->session_1_start))) {
                $s2Start->addDay();
                $s2End->addDay();
            }

            $actIn = Carbon::parse($sorted[2]->recorded_at);
            $actOut = Carbon::parse($sorted[3]->recorded_at);

            $start = $actIn->gt($s2Start) ? $actIn : $s2Start;
            $end = $actOut->lt($s2End) ? $actOut : $s2End;

            if ($end->gt($start)) {
                $minutes += $start->diffInMinutes($end, true);
            }
        }

        return $minutes;
    }
}
