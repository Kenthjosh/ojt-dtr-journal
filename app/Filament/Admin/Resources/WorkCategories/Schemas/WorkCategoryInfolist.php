<?php

namespace App\Filament\Admin\Resources\WorkCategories\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class WorkCategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make("Work Category Info")
                ->schema([
                    TextEntry::make("name"),
                    TextEntry::make("creator.name")
                        ->numeric()
                        ->placeholder("-"),
                    TextEntry::make("created_at")->dateTime()->placeholder("-"),
                    TextEntry::make("updated_at")->dateTime()->placeholder("-"),
                ])
                ->columnSpanFull()
                ->columns(2),
        ]);
    }
}
