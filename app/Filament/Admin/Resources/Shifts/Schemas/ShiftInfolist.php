<?php

namespace App\Filament\Admin\Resources\Shifts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class ShiftInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                TextEntry::make('name'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('session_1_start')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('session_1_end')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('session_2_start')
                    ->time()
                    ->placeholder('-'),
                TextEntry::make('session_2_end')
                    ->time()
                    ->placeholder('-'),
            ])
            ->columnSpanFull()
            ->columns(2),
            ]);
    }
}
