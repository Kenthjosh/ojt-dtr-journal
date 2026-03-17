<?php

namespace App\Filament\Admin\Resources\DtrLogs;

use App\Filament\Admin\Resources\DtrLogs\Pages\CreateDtrLog;
use App\Filament\Admin\Resources\DtrLogs\Pages\EditDtrLog;
use App\Filament\Admin\Resources\DtrLogs\Pages\ListDtrLogs;
use App\Filament\Admin\Resources\DtrLogs\Pages\ViewDtrLog;
use App\Filament\Admin\Resources\DtrLogs\Schemas\DtrLogForm;
use App\Filament\Admin\Resources\DtrLogs\Schemas\DtrLogInfolist;
use App\Filament\Admin\Resources\DtrLogs\Tables\DtrLogsTable;
use App\Models\DtrLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DtrLogResource extends Resource
{

    protected static string|\UnitEnum|null $navigationGroup = "Reports";

    protected static ?int $navigationSort = 1;

    protected static ?string $model = DtrLog::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'DtrLog';

    public static function form(Schema $schema): Schema
    {
        return DtrLogForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return DtrLogInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DtrLogsTable::configure($table);      
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
            'index' => ListDtrLogs::route('/'),
            'create' => CreateDtrLog::route('/create'),
            'view' => ViewDtrLog::route('/{record}'),
            'edit' => EditDtrLog::route('/{record}/edit'),
        ];
    }
}
