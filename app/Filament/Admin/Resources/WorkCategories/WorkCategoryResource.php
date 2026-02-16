<?php

namespace App\Filament\Admin\Resources\WorkCategories;

use App\Filament\Admin\Resources\WorkCategories\Pages\CreateWorkCategory;
use App\Filament\Admin\Resources\WorkCategories\Pages\EditWorkCategory;
use App\Filament\Admin\Resources\WorkCategories\Pages\ListWorkCategories;
use App\Filament\Admin\Resources\WorkCategories\Pages\ViewWorkCategory;
use App\Filament\Admin\Resources\WorkCategories\Schemas\WorkCategoryForm;
use App\Filament\Admin\Resources\WorkCategories\Schemas\WorkCategoryInfolist;
use App\Filament\Admin\Resources\WorkCategories\Tables\WorkCategoriesTable;
use App\Models\WorkCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WorkCategoryResource extends Resource
{
    protected static ?int $navigationSort = 5;

    protected static ?string $model = WorkCategory::class;
    protected static string|\UnitEnum|null $navigationGroup = "Administration";

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = "WorkCategory";

    public static function form(Schema $schema): Schema
    {
        return WorkCategoryForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WorkCategoryInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WorkCategoriesTable::configure($table);
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
            "index" => ListWorkCategories::route("/"),
            "create" => CreateWorkCategory::route("/create"),
            "view" => ViewWorkCategory::route("/{record}"),
            "edit" => EditWorkCategory::route("/{record}/edit"),
        ];
    }
}
