<?php

namespace App\Filament\Exports;

use App\Models\Loan;
use Carbon\Carbon;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class LoanExporter extends Exporter
{
    protected static ?string $model = Loan::class;

    public function getFileName(Export $export): string
    {
        $now = Carbon::now();
        return "loans-$now";
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),
            ExportColumn::make('user.name')->label('Member'),
            ExportColumn::make('book.title')->label('book'),
            ExportColumn::make('borrow_date'),
            ExportColumn::make('return_date'),
            ExportColumn::make('actual_return'),
            ExportColumn::make('status'),
            ExportColumn::make('late_fee'),
            ExportColumn::make('deleted_at')->enabledByDefault(false),
            ExportColumn::make('created_at')->enabledByDefault(false),
            ExportColumn::make('updated_at')->enabledByDefault(false),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your loan export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
