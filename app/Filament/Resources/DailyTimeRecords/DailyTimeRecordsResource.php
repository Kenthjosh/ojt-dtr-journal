<?php

namespace App\Filament\Resources\DailyTimeRecords;

use App\Filament\Resources\DailyTimeRecords\Pages\ListDailyTimeRecords;
use App\Filament\Resources\DailyTimeRecords\Schemas\DailyTimeRecordsForm;
use App\Filament\Resources\DailyTimeRecords\Schemas\DailyTimeRecordsInfolist;
use App\Filament\Resources\DailyTimeRecords\Tables\DailyTimeRecordsTable;
use App\Models\DailyTimeRecords;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DailyTimeRecordsResource extends Resource
{
    protected static ?string $model = DailyTimeRecords::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

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