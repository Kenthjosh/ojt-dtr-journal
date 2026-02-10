<?php

namespace App\Filament\Admin\Resources\WeeklyReports\Tables;

use App\Services\Exports\WeeklyReportsExportService;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'viewed' => 'info',
                        'certified' => 'success',
                    }),
                TextColumn::make('updated_at')
                    ->label('Submitted at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('viewed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('submitted_at')
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
                    ]),
                Filter::make('week_range')
                    ->form([
                        DatePicker::make('from')
                            ->label('From'),
                        DatePicker::make('until')
                            ->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $query, $date) => $query->whereDate('week_start', '>=', $date)
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (Builder $query, $date) => $query->whereDate('week_end', '<=', $date)
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->color('info'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('exportSelected')
                        ->label('Export Selected')
                        ->icon('heroicon-o-archive-box-arrow-down')
                        ->color('success')
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
                            Notification::make()
                                ->title('Export Started')
                                ->body('Your Export file is being generated...')
                                ->success()
                                ->send();
                                $path = app(WeeklyReportsExportService::class)
                                ->exportCertifiedReports($reports);
                        
                            return redirect()->route('exports.download', [
                                'path' => encrypt($path),
                            ]);
                        }),
                ]),
            ]);
    }
}
