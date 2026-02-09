<?php

namespace App\Filament\Intern\Resources\WeeklyReports\Tables;

use App\Services\Exports\WeeklyReportsExportService;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Support\Enums\TextSize;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

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
                    ->label('Submitted')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'viewed' => 'warning',
                        'certified' => 'success',
                    }),
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
                    ->hidden(fn ($record): bool => $record->status === 'certified'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('exportSelected')
                        ->label('Export Selected')
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->modalHeading('Export Weekly Reports')
                        ->modalDescription(new \Illuminate\Support\HtmlString('Keep in mind this will only export <span style="color:rgb(51, 255, 0);">certified</span> reports'))
                        ->action(function (\Illuminate\Support\Collection $reports) {
                            $certified = $reports->where('status', 'certified');

                            if ($certified->isEmpty()) {
                                Notification::make()
                                    ->title('Nothing to export')
                                    ->body('The selected reports are not certified.')
                                    ->warning()
                                    ->send();

                                return;
                            }

                            app(WeeklyReportsExportService::class)
                                ->exportCertifiedReports($reports);
                            Notification::make()
                                ->title('Export Started')
                                ->body('Your Export file is being generated...')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }
}
