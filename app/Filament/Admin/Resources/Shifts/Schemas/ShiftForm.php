<?php

namespace App\Filament\Admin\Resources\Shifts\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class ShiftForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                TextInput::make('name')
                    ->required(),
                TimePicker::make('session_1_start'),
                TimePicker::make('session_1_end'),
                TimePicker::make('session_2_start'),
                TimePicker::make('session_2_end'),
            ])
            ->columnSpanFull()
            ->columns(2),
            ]);
    }
}
