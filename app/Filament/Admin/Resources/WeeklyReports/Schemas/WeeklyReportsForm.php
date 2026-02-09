<?php

namespace App\Filament\Admin\Resources\WeeklyReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class WeeklyReportsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                DatePicker::make('week_start')
                    ->required(),
                DatePicker::make('week_end')
                    ->required(),
                TextInput::make('status')
                    ->required(),
                DateTimePicker::make('submitted_at'),
                DateTimePicker::make('viewed_at'),
                DateTimePicker::make('certified_at'),
                TextInput::make('certified_by')
                    ->required()
                    ->numeric(),
                TextInput::make('signature'),
                Textarea::make('entries')
                    ->columnSpanFull(),
            ]);
    }
}
