<?php

namespace App\Filament\Admin\Resources\WeeklyReports\Schemas;

use App\Models\WeeklyReports;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class WeeklyReportsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('week_start')
                    ->date(),
                TextEntry::make('week_end')
                    ->date(),
                TextEntry::make('status'),
                TextEntry::make('submitted_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('viewed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('certified_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('certified_by')
                    ->numeric(),
                TextEntry::make('signature')
                    ->placeholder('-'),
                TextEntry::make('entries')
                // Erase the line below if error related sa pagview
                    ->formatStateUsing(fn ($state) => json_encode($state))
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (WeeklyReports $record): bool => $record->trashed()),
            ]);
    }
}
