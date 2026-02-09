<?php

namespace App\Filament\Admin\Resources\Attendances\Schemas;

use App\Models\Attendances;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AttendancesInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('User'),
                        TextEntry::make('date')
                            ->date(),
                        TextEntry::make('time_in')
                            ->time()
                            ->placeholder('-'),
                        TextEntry::make('time_out')
                            ->time()
                            ->placeholder('-'),
                        TextEntry::make('hours_rendered')
                            ->numeric()
                            ->placeholder('-'),
                        TextEntry::make('notes')
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->dateTime()
                            ->visible(fn (Attendances $record): bool => $record->trashed()),
                    ])
                    ->columns(2),
            ]);
    }
}
