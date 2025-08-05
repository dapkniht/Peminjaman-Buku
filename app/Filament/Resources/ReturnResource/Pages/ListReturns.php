<?php

namespace App\Filament\Resources\ReturnResource\Pages;

use App\Filament\Resources\ReturnResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListReturns extends ListRecords
{
    protected static string $resource = ReturnResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make(),
            'borrowed' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'borrowed')),
            'returned' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'returned')),
            'late' => Tab::make()
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', 'late')),
        ];
    }
}
