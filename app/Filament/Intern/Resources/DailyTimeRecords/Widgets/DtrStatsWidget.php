<?php

namespace App\Filament\Intern\Resources\DailyTimeRecords\Widgets;

use App\Models\DtrLog;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class DtrStatsWidget extends StatsOverviewWidget
{
    protected $listeners = ['refreshWidgets' => '$refresh'];

    protected function getStats(): array
    {
        $userId = Auth::id();
        $targetMinutes = 729 * 60; // 43,740 total minutes

        $stats = DtrLog::where('user_id', $userId)
            ->selectRaw('COALESCE(SUM(work_minutes), 0) as total_work')
            ->first();

        $totalRendered = (int) $stats->total_work;
        $remaining = max(0, $targetMinutes - $totalRendered);

        $totalDays = DtrLog::where('user_id', $userId)
            ->distinct('work_date')
            ->count('work_date');

        return [
            Stat::make('Total Hours Rendered', $this->formatTime($totalRendered))
                ->description('Accumulated credited time')
                ->color('success'),

            Stat::make('Remaining Hours', $this->formatTime($remaining))
                ->description('Countdown to 729h target')
                ->color($remaining > 0 ? 'warning' : 'success')
                ->chart([$remaining, $remaining * 0.8, $remaining * 0.5, 0]),

            Stat::make('Total Days Worked', (string) $totalDays)
                ->description('Unique business days recorded'),
        ];
    }

    private function formatTime(int $totalMinutes): string
    {
        if ($totalMinutes <= 0) return '0m';

        $hours = floor($totalMinutes / 60);
        $mins = $totalMinutes % 60;

        return $hours > 0 ? "{$hours}h {$mins}m" : "{$mins}m";
    }
}
