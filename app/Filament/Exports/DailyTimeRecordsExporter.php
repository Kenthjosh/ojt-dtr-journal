<?php

namespace App\Filament\Exports;

use App\Models\DtrLog;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Carbon;
use Illuminate\Support\Number;
use OpenSpout\Common\Entity\Style\CellAlignment;
use OpenSpout\Common\Entity\Style\CellVerticalAlignment;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Options;

class DailyTimeRecordsExporter extends Exporter
{
    protected static ?string $model = DtrLog::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('No'),
            ExportColumn::make('user.name')
                ->label('Name'),
            ExportColumn::make('work_date')
                ->label('Date')
                ->formatStateUsing(function ($state) {
                    return $state ? Carbon::parse($state)->format('M d, Y') : 'N/A';
                }),
            ExportColumn::make('recorded_at')
                ->label('Time')
                ->formatStateUsing(function ($state) {
                    return $state ? Carbon::parse($state)->format('hi: A') : 'N/A';
                }),
            ExportColumn::make('type')
                ->label('Type')
                ->formatStateUsing(fn ($state) => $state === 1 ? '[IN]' : '[OUT]'),
        ];
    }

    public function getXlsxWriterOptions(): ?Options
    {
        $options = new Options;
        $columnWidths = [0, 5, 30, 15, 15, 10];

        foreach ($columnWidths as $index => $width) {
            $options->setColumnWidth($width, $index);
        }

        return $options;
    }

    public function getXlsxCellStyle(): ?Style
    {
        return (new Style)
            ->setCellAlignment('center')
            ->setBackgroundColor(Color::rgb(186, 193, 231));
    }

    public function getXlsxHeaderCellStyle(): ?Style
    {
        return (new Style)
            ->setFontBold()
            ->setFontItalic()
            ->setFontSize(12)
            ->setFontName('Consolas')
            ->setFontColor(Color::rgb(18, 21, 39))
            ->setBackgroundColor(Color::rgb(54, 145, 219))
            ->setCellAlignment(CellAlignment::CENTER)
            ->setCellVerticalAlignment(CellVerticalAlignment::CENTER);
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your daily time records export has completed and '.Number::format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
