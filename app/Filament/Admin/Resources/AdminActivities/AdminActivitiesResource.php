<?php

namespace App\Filament\Admin\Resources\AdminActivities;

use App\Filament\Admin\Resources\AdminActivities\Pages\CreateAdminActivities;
use App\Filament\Admin\Resources\AdminActivities\Pages\EditAdminActivities;
use App\Filament\Admin\Resources\AdminActivities\Pages\ListAdminActivities;
use App\Filament\Admin\Resources\AdminActivities\Pages\ViewAdminActivities;
use App\Filament\Admin\Resources\AdminActivities\Schemas\AdminActivitiesForm;
use App\Filament\Admin\Resources\AdminActivities\Schemas\AdminActivitiesInfolist;
use App\Filament\Admin\Resources\AdminActivities\Tables\AdminActivitiesTable;
use App\Models\AdminActivities;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AdminActivitiesResource extends Resource
{
    protected static ?int $navigationSort = 3;

    protected static ?string $model = AdminActivities::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    protected static ?string $recordTitleAttribute = 'AdminActivities';

    public static function form(Schema $schema): Schema
    {
        return AdminActivitiesForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return AdminActivitiesInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdminActivitiesTable::configure($table);
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
            'index' => ListAdminActivities::route('/'),
            'create' => CreateAdminActivities::route('/create'),
            'view' => ViewAdminActivities::route('/{record}'),
            'edit' => EditAdminActivities::route('/{record}/edit'),
        ];
    }
}
