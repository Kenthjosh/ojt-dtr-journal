<?php

namespace App\Filament\Admin\Resources\WeeklyReports\Pages;

use App\Filament\Actions\ExportCertifiedReportsAction;
use App\Filament\Admin\Resources\WeeklyReports\WeeklyReportsResource;
use App\Traits\LogsAdminAction;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWeeklyReports extends ViewRecord
{
    use LogsAdminAction;

    protected static string $resource = WeeklyReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('certify')
                ->label('Certify')
                ->icon('heroicon-m-check')
                ->color('success')
                ->disabled(fn ($record) => $record->certified_at !== null)
                ->tooltip(fn ($record) => $record->certified_at !== null
                        ? 'Cannot edit certified reports'
                        : null
                )
                ->requiresConfirmation()
                ->action(function ($record) {
                    // Update the report
                    $record->certified_by = auth()->guard()->id();
                    $record->certified_at = now();
                    $record->viewed_at = now();
                    $record->status = 'certified';
                    $record->updated_at = now();
                    $record->save();

                    // Log the action
                    $this->logAdminAction('certified', $record);
                }),

            Action::make('mark_viewed')
                ->label('Mark as Viewed')
                ->icon('heroicon-m-eye')
                ->color('info')
                ->disabled(fn ($record) => $record->certified_at !== null)
                ->tooltip(fn ($record) => $record->certified_at !== null
                        ? 'Cannot edit certified reports'
                        : null
                )
                ->requiresConfirmation()
                ->action(function ($record) {
                    // Update the report
                    $record->viewed_at = now();
                    $record->status = 'viewed';
                    $record->updated_at = now();
                    $record->save();

                    // Log the action
                    $this->logAdminAction('viewed', $record);
                }),
            EditAction::make(),
            ExportCertifiedReportsAction::make('Export'),
        ];
    }
}
