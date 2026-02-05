<?php

namespace App\Filament\Intern\Resources\DailyTimeRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Container\Attributes\Auth;

class DailyTimeRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('recorded_at')
                    ->label('Time')
                    ->dateTime('h:i A'),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state === 1 ? 'In' : 'Out')
                    ->color(fn($state) => $state === 1 ? 'success' : 'warning')
            ])->defaultSort('recorded_at', direction: 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                // EditAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                //     DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
