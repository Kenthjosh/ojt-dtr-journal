<?php

namespace App\Filament\Admin\Resources\DtrLogs\Tables;

use App\Filament\Exports\DailyTimeRecordsExporter;
use Filament\Actions\ExportBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DtrLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('recorded_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('work_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('type')
                    ->numeric()
                    ->sortable(),
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
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('shift_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('late_minutes')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('recorded_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
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
