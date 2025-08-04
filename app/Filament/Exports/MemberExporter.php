<?php

namespace App\Filament\Exports;

use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Database\Eloquent\Builder;

class MemberExporter extends Exporter
{
    protected static ?string $model = User::class;

    public function getFileName(Export $export): string
    {
        $now = Carbon::now();
        return "members-$now";
    }

    public static function modifyQuery(Builder $query): Builder
    {
        return  $query->whereHas('roles', fn($q) => $q->where('name', 'member'));
    }

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),
            ExportColumn::make('name'),
            ExportColumn::make('email'),
            ExportColumn::make('nisn'),
            ExportColumn::make('kelas'),
            ExportColumn::make('deleted_at')->enabledByDefault(false),
            ExportColumn::make('created_at')->enabledByDefault(false),
            ExportColumn::make('updated_at')->enabledByDefault(false),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your member export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
