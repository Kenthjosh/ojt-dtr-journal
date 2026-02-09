<?php

namespace App\Filament\Intern\Resources\WeeklyReports\Pages;

use App\Filament\Intern\Resources\WeeklyReports\WeeklyReportsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ListWeeklyReports extends ListRecords
{
    protected static string $resource = WeeklyReportsResource::class;

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->where('user_id', Auth::id());
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
