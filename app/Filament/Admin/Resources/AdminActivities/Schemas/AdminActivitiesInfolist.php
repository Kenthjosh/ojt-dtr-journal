<?php

namespace App\Filament\Admin\Resources\AdminActivities\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AdminActivitiesInfolist
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
                        TextEntry::make('created_at')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('subject_type')
                            ->placeholder('-'),
                        TextEntry::make('subject_id')
                            ->numeric(),
                    ])
                    ->columns(2),
            ]);
    }
}
