<?php

namespace App\Filament\Exports;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\CellVerticalAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Options;
use Illuminate\Support\Carbon;
use App\Models\WeeklyReports;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;
use Illuminate\Database\Eloquent\Builder;

class WeeklyReportsExporter extends Exporter
{
    protected static ?string $model = WeeklyReports::class;

    public static function getQuery(): Builder
    {
        return WeeklyReports::query()
        ->where('status','certified');
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('user.name'),
            ExportColumn::make('week_start')
            ->formatStateUsing(function ($state) {
                return $state ? Carbon::parse($state)->format('M d, Y') : 'N/A';
            }),
            ExportColumn::make('week_end')
            ->formatStateUsing(function ($state) {
                return $state ? Carbon::parse($state)->format('M d, Y') : 'N/A';
            }),
            ExportColumn::make('status'),
            ExportColumn::make('submitted_at')
            ->formatStateUsing(function ($state) {
                return $state ? Carbon::parse($state)->format('M d, Y') : 'N/A';
            }),
            ExportColumn::make('viewed_at')
            ->formatStateUsing(function ($state) {
                return $state ? Carbon::parse($state)->format('M d, Y') : 'N/A';
            }),
            ExportColumn::make('certified_at')
            ->formatStateUsing(function ($state) {
                return $state ? Carbon::parse($state)->format('M d, Y') : 'N/A';
            }),
            ExportColumn::make('certified_by'),
            ExportColumn::make('signature'),
            ExportColumn::make('entries'),
            ExportColumn::make('created_at')
                ->formatStateUsing(fn(string $state): string => Carbon::parse($state)->format('M d, Y')),
            ExportColumn::make('updated_at')
                ->formatStateUsing(fn(string $state): string => Carbon::parse($state)->format('M d, Y')),
            ExportColumn::make('deleted_at')
                ->formatStateUsing(function ($state) {
                    return $state ? Carbon::parse($state)->format('M d, Y') : 'N/A';
                }),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your weekly reports export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    public function getXlsxWriterOptions(): ?Options
    {
        $options = new Options();
        $columnWidths = [0, 5, 25, 15, 15, 15, 20, 20, 20, 15, 25, 15, 15, 15,15];

        foreach ($columnWidths as $index => $width) {
            $options->setColumnWidth($width, $index);
        }

        return $options;
    }

    public function getXlsxHeaderCellStyle(): ?Style
    {
        return (new Style())
            ->setFontBold()
            ->setFontItalic()
            ->setFontSize(12)
            ->setFontName('Consolas')
            ->setFontColor(Color::rgb(18, 21, 39))
            ->setBackgroundColor(Color::rgb(54, 145, 219))
            ->setCellAlignment(CellAlignment::CENTER)
            ->setCellVerticalAlignment(CellVerticalAlignment::CENTER);
    }

    public function getXlsxCellStyle(): ?Style
    {
        return (new Style())
            ->setFontSize(12)
            ->setFontName('Consolas');
    }

}

