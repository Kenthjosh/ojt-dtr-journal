<?php

namespace App\Filament\Actions;

use App\Models\WeeklyReports;
use App\Services\Exports\WeeklyReportsExportService;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ExportCertifiedReportsAction
{
    public static function make(string $name = 'export'): Action
    {
        return Action::make($name)
            ->icon('heroicon-o-archive-box-arrow-down')
            ->color('success')
            ->disabled(fn ($record) => $record->status === 'pending' || $record->status === 'viewed')
            ->requiresConfirmation()
            ->tooltip(fn ($record) => $record->status === 'pending' || $record->status === 'viewed' ? 'Cannot export when not certified' : null)
            ->modalHeading('Export Weekly Reports')
            ->modalDescription(new HtmlString('Keep in mind this will only export <span style="color:rgb(51, 255, 0);">certified</span> reports'))
            ->action(function (WeeklyReports $report) {
                if ($report->status === ! 'certified') {
                    Notification::make()
                        ->title('Cannot Export File')
                        ->body('This report not certified.')
                        ->warning()
                        ->send();

                    return;
                }
                Notification::make()
                    ->title('File Successfully Exported!')
                    ->icon('heroicon-o-document-text')
                    ->iconColor('success')
                    ->success()
                    ->send();

                $path = app(WeeklyReportsExportService::class)
                    ->exportCertifiedReports(collect([$report]));
                    return redirect()->route('exports.download', [
                        'path' => encrypt($path),
                    ]);
            });
    }
}
