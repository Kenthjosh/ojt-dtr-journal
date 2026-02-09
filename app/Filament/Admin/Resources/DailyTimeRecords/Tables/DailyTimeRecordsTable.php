<?php

namespace App\Filament\Admin\Resources\DailyTimeRecords\Tables;

use App\Filament\Exports\DailyTimeRecordsExporter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ExportBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DailyTimeRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Date')
                    ->date()
                    ->sortable(),
                TextColumn::make('recorded_at')
                    ->label('Time')
                    ->dateTime('h:i A'),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state === 1 ? 'In' : 'Out')
                    ->color(fn ($state) => $state === 1 ? 'success' : 'warning'),
            ])->defaultSort('recorded_at', direction: 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make('export')
                        ->label('Export Selected')
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->color('success')
                        ->exporter(DailyTimeRecordsExporter::class)
                        ->maxRows(500)
                        ->columnMapping(false),
                ]),
            ]);
    }
}
