<?php

namespace App\Filament\Admin\Resources\Shifts;

use App\Filament\Admin\Resources\Shifts\Pages\CreateShift;
use App\Filament\Admin\Resources\Shifts\Pages\EditShift;
use App\Filament\Admin\Resources\Shifts\Pages\ListShifts;
use App\Filament\Admin\Resources\Shifts\Pages\ViewShift;
use App\Filament\Admin\Resources\Shifts\Schemas\ShiftForm;
use App\Filament\Admin\Resources\Shifts\Schemas\ShiftInfolist;
use App\Filament\Admin\Resources\Shifts\Tables\ShiftsTable;
use App\Models\Shift;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\GlobalSearch\GlobalSearchResult;
use UnitEnum;

class ShiftResource extends Resource
{
    protected static ?string $model = Shift::class;

    protected static ?int $navigationSort = 7;

    protected static string|UnitEnum|null $navigationGroup = "Administration";

    public static function getGloballySearchableAttributes(): array
        {
            return ['name']; // ✅ correct column
        }

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static ?string $recordTitleAttribute = 'Shift';

    public static function form(Schema $schema): Schema
    {
        return ShiftForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return ShiftInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShiftsTable::configure($table);
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
            'index' => ListShifts::route('/'),
            'create' => CreateShift::route('/create'),
            'view' => ViewShift::route('/{record}'),
            'edit' => EditShift::route('/{record}/edit'),
        ];
    }
}
