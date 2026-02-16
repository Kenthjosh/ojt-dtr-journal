<?php

namespace App\Filament\Admin\Resources\WorkCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class WorkCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make("")
                ->schema([
                    TextInput::make("Category")
                        ->label(
                            fn(string $operation) => $operation === "create"
                                ? "Create a new Work Category"
                                : "Enter New Work Category",
                        )
                        ->required(),
                ])
                ->columnSpanFull(),
        ]);
    }
}
