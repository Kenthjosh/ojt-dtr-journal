<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextEntry::make('name'),
                        TextEntry::make('email')
                            ->label('Email address'),
                        TextEntry::make('email_verified_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('role')
                            ->badge(),
                        TextEntry::make('shift_id')
                            ->label('Shift')
                            ->placeholder('-')
                            ->getStateUsing(fn ($record) => match ($record->shift_id) {
                                1 => 'Day',
                                2 => 'Night',
                                default => '-', // fallback if null or unexpected value
                            })
                            ->color(fn ($record) => match ($record->shift_id) {
                                1 => 'Day',   // yellow
                                2 => 'Night',
                                default => 'gray',
                            }),
                    ])
                    ->columnSpanFull()
                    ->columns(2),
            ]);
    }
}
