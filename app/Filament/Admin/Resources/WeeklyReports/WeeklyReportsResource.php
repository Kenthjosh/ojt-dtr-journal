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

class WeeklyReportsResource extends Resource
{
    protected static ?string $model = WeeklyReports::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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
