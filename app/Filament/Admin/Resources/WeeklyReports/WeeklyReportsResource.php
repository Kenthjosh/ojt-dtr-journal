<?php

namespace App\Filament\Admin\Resources\WeeklyReports;

use App\Filament\Admin\Resources\WeeklyReports\Pages\EditWeeklyReports;
use App\Filament\Admin\Resources\WeeklyReports\Pages\ListWeeklyReports;
use App\Filament\Admin\Resources\WeeklyReports\Pages\ViewWeeklyReports;
use App\Filament\Admin\Resources\WeeklyReports\Schemas\WeeklyReportsForm;
use App\Filament\Admin\Resources\WeeklyReports\Schemas\WeeklyReportsInfolist;
use App\Filament\Admin\Resources\WeeklyReports\Tables\WeeklyReportsTable;
use App\Models\WeeklyReports;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Filament\GlobalSearch\GlobalSearchResult;
use Illuminate\Support\Collection;
use App\Models\WeeklyReport;

class WeeklyReportsResource extends Resource
{
    public static function getGloballySearchableAttributes(): array
    {
        return ['user.name', 'status']; // change to a column that exists in your table
    }

    public static function getGlobalSearchResults(string $search): Collection
    {
        $searchLower = strtolower($search);
        $results = collect();

        // Search weekly reports by user name or status
        $reports = WeeklyReports::whereHas('user', function ($q) use ($searchLower) {
            $q->where('name', 'like', "%{$searchLower}%");
        })
        ->orWhere('status', 'like', "%{$searchLower}%")
        ->get();

        foreach ($reports as $report) {
            $results->push(
                new GlobalSearchResult(
                    "Weekly Report: {$report->user->name} — {$report->status}",
                    WeeklyReportsResource::getUrl('index', ['search' => $report->user->name])
                )
            );
        }

        return $results;
    }
    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 2;

    protected static ?string $model = WeeklyReports::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $recordTitleAttribute = 'WeeklyReports';

    public static function form(Schema $schema): Schema
    {
        return WeeklyReportsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WeeklyReportsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WeeklyReportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWeeklyReports::route('/'),
            'view' => ViewWeeklyReports::route('/{record}'),
            'edit' => EditWeeklyReports::route('/{record}/edit'),
        ];
    }
}
