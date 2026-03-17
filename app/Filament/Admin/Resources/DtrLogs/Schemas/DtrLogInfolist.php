<?php

namespace App\Filament\Admin\Resources\DtrLogs\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DtrLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('type')
                    ->numeric(),
                TextEntry::make('recorded_at')
                    ->dateTime(),
                TextEntry::make('work_date')
                    ->date(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('shift_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('work_minutes')
                    ->numeric(),
                TextEntry::make('late_minutes')
                    ->numeric(),
            ]);
    }
}
