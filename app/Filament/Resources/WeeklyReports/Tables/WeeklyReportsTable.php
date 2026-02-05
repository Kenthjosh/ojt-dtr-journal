<?php

namespace App\Filament\Resources\WeeklyReports\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WeeklyReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
        ->heading('Weekly Reports')
        ->description('Containing a lists of user weekly reports.')
            ->columns([
                TextColumn::make('user.name')
                    ->searchable(),
                TextColumn::make('week_start')
                    ->date()
                    ->sortable(),
                TextColumn::make('week_end')
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'viewed' => 'info',
                        'certified' => 'success',
                    }),
                TextColumn::make('submitted_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('viewed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('certified_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('certified_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('signature')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->striped()
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'certified' => 'Certified',
                        'pending' => 'Pending',
                        'viewed' => 'Viewed',
                    ])
            ])
            ->recordActions([
            ])
            ->toolbarActions([
                BulkActionGroup::make([])
            ]);
    }
}
