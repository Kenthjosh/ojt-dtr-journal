<?php

namespace App\Filament\Admin\Resources\Attendances;

use App\Filament\Admin\Resources\Attendances\Pages\CreateAttendances;
use App\Filament\Admin\Resources\Attendances\Pages\EditAttendances;
use App\Filament\Admin\Resources\Attendances\Pages\ListAttendances;
use App\Filament\Admin\Resources\Attendances\Pages\ViewAttendances;
use App\Filament\Admin\Resources\Attendances\Schemas\AttendancesForm;
use App\Filament\Admin\Resources\Attendances\Schemas\AttendancesInfolist;
use App\Filament\Admin\Resources\Attendances\Tables\AttendancesTable;
use App\Models\Attendances;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AttendancesResource extends Resource
{
    protected static ?int $navigationSort = 2;

    protected static ?string $model = Attendances::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $recordTitleAttribute = 'Attendances';

    public static function form(Schema $schema): Schema
    {
        return AttendancesForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AttendancesInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendancesTable::configure($table);
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
            'index' => ListAttendances::route('/'),
            'create' => CreateAttendances::route('/create'),
            'view' => ViewAttendances::route('/{record}'),
            'edit' => EditAttendances::route('/{record}/edit'),
        ];
    }
}
