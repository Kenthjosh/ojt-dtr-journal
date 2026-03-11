<?php

namespace App\Filament\Intern\Resources\DailyTimeRecords\Widgets;

use App\Models\DtrLog;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DtrStatsWidget extends StatsOverviewWidget
{
    // Ensure the widget refreshes when the buttons are clicked
    protected $listeners = ['refreshWidgets' => '$refresh'];

    protected function getStats(): array
    {
        $userId = Auth::id();

        // 1. Force the values to integers and handle nulls inside the query
        $stats = DtrLog::where('user_id', $userId)
            ->selectRaw('COALESCE(SUM(work_minutes), 0) as total_work')
            ->selectRaw('COALESCE(SUM(late_minutes), 0) as total_late')
            ->first();

        // 2. Debugging (Optional): If it still shows 0, uncomment the next line to see what's inside $stats
        // dd($stats->toArray()); 

        $totalDays = DtrLog::where('user_id', $userId)
            ->distinct('work_date')
            ->count('work_date');

        return [
            Stat::make('Total Hours', $this->formatTime((int) $stats->total_work))
                ->description('Accumulated credited work time')
                ->color('success'),

            Stat::make('Total Days', (string) $totalDays)
                ->description('Active duty days recorded'),

            Stat::make('Overall Late', $this->formatTime((int) $stats->total_late))
                ->description('Total minutes behind schedule')
                ->color($stats->total_late > 0 ? 'danger' : 'gray'),
        ];
    }

    private function formatTime(?int $totalMinutes): string
    {
        $totalMinutes = $totalMinutes ?? 0;

        if ($totalMinutes <= 0) {
            return '0';
        }

        $hours = floor($totalMinutes / 60);
        $mins = $totalMinutes % 60;

        // Returns format: 8h 0m or 45m if less than an hour
        if ($hours > 0) {
            return "{$hours}h {$mins}m";
        }

        return "{$mins}m";
    }
}
