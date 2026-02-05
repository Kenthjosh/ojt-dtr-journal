<?php

namespace App\Filament\Resources\WeeklyReports\Pages;

use App\Filament\Exports\WeeklyReportsExporter;
use App\Services\Exports\WeeklyReportsExportService;
use Filament\Actions\Action;
use Filament\Actions\ExportAction;
use App\Filament\Resources\WeeklyReports\WeeklyReportsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWeeklyReports extends ListRecords
{
    protected static string $resource = WeeklyReportsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // ExportAction::make()
            //     ->exporter(WeeklyReportsExporter::class)
            //     ->maxRows(500)
            //     ->columnMapping(false)
            //     ->requiresConfirmation()
            //     ->modalHeading('Export Weekly Reports')
            //     ->modalDescription('Keep in mind this will only export [certified] reports'),
                Action::make("Export")
                ->label("Export")
                ->action(function ($reports) {
                    app(WeeklyReportsExportService::class)
                    ->exportCertifiedReports($reports);
                })
                ->requiresConfirmation()
                ->modalHeading('Export Weekly Reports')
                ->modalDescription('Keep in mind this will only export [certified] reports'),
        ];
    }
}
