<?php

namespace App\Filament\Imports;

use App\Models\Loan;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class LoanImporter extends Importer
{
    protected static ?string $model = Loan::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('user')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('book')
                ->requiredMapping()
                ->relationship()
                ->rules(['required']),
            ImportColumn::make('borrow_date')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('return_date')
                ->requiredMapping()
                ->rules(['required', 'date']),
            ImportColumn::make('actual_return')
                ->rules(['date']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('late_fee')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): ?Loan
    {
        // return Loan::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Loan();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your loan import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
