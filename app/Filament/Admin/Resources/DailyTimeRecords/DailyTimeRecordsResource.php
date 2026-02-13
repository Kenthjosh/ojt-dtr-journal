<?php

namespace App\Filament\Admin\Resources\DailyTimeRecords;

use App\Filament\Admin\Resources\DailyTimeRecords\Pages\ListDailyTimeRecords;
use App\Filament\Admin\Resources\DailyTimeRecords\Schemas\DailyTimeRecordsForm;
use App\Filament\Admin\Resources\DailyTimeRecords\Schemas\DailyTimeRecordsInfolist;
use App\Filament\Admin\Resources\DailyTimeRecords\Tables\DailyTimeRecordsTable;
use App\Models\DtrLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Filament\GlobalSearch\GlobalSearchResult;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;

class DailyTimeRecordsResource extends Resource
{

    public static function getGloballySearchableAttributes(): array
    {
        // Keep columns that exist in DB
        return ['user.name'];
    }


    public static function getGlobalSearchResults(string $search): Collection
    {
        $searchLower = strtolower($search);

        $results = collect();

        // Only users that have at least one matching DTR log
        $users = User::whereHas('dtrLogs', function ($q) use ($searchLower) {
            $q->whereHas('user', fn($q2) => $q2->where('name', 'like', "%{$searchLower}%"));
        })->get();

        foreach ($users as $user) {
            $results->push(
                new GlobalSearchResult(
                    "DTR Logs: {$user->name}",
                    DailyTimeRecordsResource::getUrl('index', ['search' => $user->name])
                )
            );
        }

        return $results;
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (request()->filled('search')) {
            $search = request('search');

            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        return $query;
    }
    

    protected static string|UnitEnum|null $navigationGroup = 'Reports';

    protected static ?int $navigationSort = 1;

    protected static ?string $model = DtrLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $recordTitleAttribute = 'Daily Time Records';

    public static function form(Schema $schema): Schema
    {
        return DailyTimeRecordsForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DailyTimeRecordsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DailyTimeRecordsTable::configure($table);
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
            'index' => ListDailyTimeRecords::route('/'),
        ];
    }
}
