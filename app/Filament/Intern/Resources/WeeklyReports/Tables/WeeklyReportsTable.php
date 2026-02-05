<?php

namespace App\Filament\Intern\Resources\WeeklyReports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Support\Enums\TextSize;
use App\Models\WeeklyReports;

class WeeklyReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('journal_number')
                    ->size(TextSize::Medium)
                    ->label('Journal Number')
                    ->searchable(),
                TextColumn::make('week_start')
                    ->date('M j, Y')
                    ->label('Week start')
                    ->searchable(),
                TextColumn::make('week_end')
                    ->date('M j, Y')
                    ->label('Week end')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->date('M j, Y')
                    ->label('Created')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'viewed' => 'warning',
                        'certified' => 'success',
                    })
            ])->striped()
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'pending',
                        'viewed' => 'viewed',
                        'certified' => 'certified',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make()
                    ->hidden(fn($record): bool => $record->status === 'certified'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
