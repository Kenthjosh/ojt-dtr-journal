<?php

namespace App\Filament\Resources\DailyTimeRecords\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class DailyTimeRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->heading('Daily Time Records')
            ->columns([
                TextColumn::make('id')
                ->label('ID')
                ->sortable(),
                TextColumn::make('user.name')
                ->label('Name')
                ->searchable(),
                TextColumn::make('date')
                ->label('Date')
                ->date(),
                TextColumn::make('time')
                ->label('Time')
                ->dateTime('h:i A'),
                TextColumn::make('type')
                ->label('Type')
                ->formatStateUsing(fn($state) => $state === 1? 'In':'Out')
                ->badge()
                ->color(fn($state) => $state === 1? 'success': 'warning'),
            ])
            ->recordActions([
            ])
            ->toolbarActions([
            ]);
    }
}
