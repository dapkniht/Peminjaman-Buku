<?php

namespace App\Filament\Resources\LoanResource\Widgets;

use App\Enums\LoanStatus;
use App\Models\Loan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LoanOverview extends BaseWidget
{
    protected int | string | array $columnSpan = [
        'md' => 2,
        'xl' => 3,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Loan::query()
            )
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->copyable()
                    ->copyMessage('Id copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('user.name')
                    ->label("Member")
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Member copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('book.title')
                    ->label('Book')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Book copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('borrow_date')
                    ->label("Borrow Date")
                    ->date()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Borrow Date copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(LoanStatus $state): string => match ($state) {
                        LoanStatus::Borrowed => 'warning',
                        LoanStatus::Returned => 'success',
                        LoanStatus::Late     => 'danger',
                    })
                    ->copyable()
                    ->copyMessage('Status copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->copyable()
                    ->copyMessage('Created At copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->copyable()
                    ->copyMessage('Updated At copied')
                    ->copyMessageDuration(1500),
            ])->defaultSort('created_at', 'desc');
    }
}
