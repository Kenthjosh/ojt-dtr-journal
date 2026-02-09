<?php

namespace App\Filament\Admin\Resources\WeeklyReports\Pages;

use App\Filament\Admin\Resources\WeeklyReports\WeeklyReportsResource;
use App\Filament\Admin\Resources\WeeklyReports\Widgets\StatsOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWeeklyReports extends ListRecords
{
    protected static string $resource = WeeklyReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::class,
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }
}
