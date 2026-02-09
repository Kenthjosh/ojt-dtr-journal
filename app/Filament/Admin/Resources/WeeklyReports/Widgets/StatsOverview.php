<?php

namespace App\Filament\Admin\Resources\WeeklyReports\Widgets;

use App\Models\WeeklyReports;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $pendingReports = WeeklyReports::where('status', 'pending')->count();
        $viewedReports = WeeklyReports::where('status', 'viewed')->count();
        $reportsThisWeek = WeeklyReports::whereBetween('created_at', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ])->count();

        return [
            Stat::make('Reports this week', $reportsThisWeek)
                ->icon('heroicon-o-document-text'),
            Stat::make('Pending Reports', $pendingReports)
                ->icon('heroicon-o-clock')
                ->color('danger'),
            Stat::make('Viewed reports', $viewedReports)
                ->icon('heroicon-m-eye'),
        ];
    }
}
