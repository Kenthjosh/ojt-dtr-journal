<?php

namespace App\Filament\Admin\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('role')
                    ->colors([
                        'warning' => 'admin',
                        'info' => 'intern',
                    ])
                    ->badge(),
                TextColumn::make('shift_id')
                    ->label('Shift')
                    ->placeholder('-')
                    ->weight('bold')
                    ->formatStateUsing(fn (int $state) => match ($state) {
                        1 => 'Day Shift',
                        2 => 'Night Shift',
                        default => ucfirst($state),
                    })
                    ->color(fn (int $state) => match ($state) {
                        1 => 'Day',   // yellow
                        2 => 'Night',
                        default => 'gray',
                    })
                    ->searchable(),
            ])
            ->filters([
                Filter::make('week_range')
                    ->form([
                        DatePicker::make('from')->label('From'),
                        DatePicker::make('until')->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(! empty($data['from']), fn ($q) => $q->whereDate('created_at', '>=', $data['from'])
                            )
                            ->when(! empty($data['until']), fn ($q) => $q->whereDate('created_at', '<=', $data['until'])
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
