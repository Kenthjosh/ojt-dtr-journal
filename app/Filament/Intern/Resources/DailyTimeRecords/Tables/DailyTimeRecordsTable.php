<?php

namespace App\Filament\Intern\Resources\DailyTimeRecords\Tables;

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
                TextColumn::make('work_date')
                    ->label('Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('recorded_at')
                    ->label('Time')
                    ->dateTime('h:i A')
                    ->sortable(),

                TextColumn::make('type')
                    ->badge()
                    // We check for the LABEL because your DtrTypeCast has already transformed 1 into "Time In"
                    ->formatStateUsing(fn($state) => $state === 'Time In' ? 'In' : 'Out')
                    ->color(fn($state) => $state === 'Time In' ? 'success' : 'info'),

                TextColumn::make('work_minutes')
                    ->label('Hours Rendered')
                    ->formatStateUsing(function ($state, $record) {

                        if ($record->type !== 'Time Out') {
                            return '-';
                        }

                        $hours = floor($state / 60);
                        $minutes = $state % 60;

                        if ($hours > 0) {
                            return "{$hours}h {$minutes}m";
                        }

                        return "{$minutes}m";
                    })
                    ->color(fn ($record) => $record->type === 'Time Out' ? 'success' : null)
                    ->alignCenter(),
            ])->defaultSort('recorded_at', direction: 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->label('Export Selected')
                        ->color('success')
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->exporter(DailyTimeRecordsExporter::class)
                        ->maxRows(500)
                        ->columnMapping(false),
                ]),
            ]);
    }
}
