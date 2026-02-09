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

class DailyTimeRecordsResource extends Resource
{
    protected static ?string $model = DtrLog::class;

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
