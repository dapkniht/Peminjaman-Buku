<?php

namespace App\Filament\Resources;

use App\Enums\LoanStatus;
use App\Filament\Exports\LoanExporter;
use App\Filament\Resources\ReturnResource\Pages;
use App\Filament\Resources\ReturnResource\RelationManagers;
use App\Models\Loan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\TrashedFilter;

class ReturnResource extends Resource
{
    protected static ?string $model = Loan::class;

    protected static ?string $navigationIcon = 'hugeicons-truck-return';
    protected static ?string $modelLabel = 'Return';
    protected static ?string $pluralModelLabel = 'Returns';
    protected static ?string $navigationLabel = 'Returns';
    protected static ?string $navigationGroup = 'Transactions';
    protected static bool $shouldSkipAuthorization = true;
    protected static ?int $navigationSort = 5;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('id')
                    ->label('Loan')
                    ->required()
                    ->searchable()
                    ->options(
                        Loan::with('user', 'book')
                            ->get()
                            ->mapWithKeys(function ($loan) {
                                return [
                                    $loan->id => $loan->user->name . ' - ' . $loan->book->title . ' - ' . $loan->borrow_date,
                                ];
                            })
                    )
                    ->preload(),
                Forms\Components\DatePicker::make('actual_return')
                    ->required()
                    ->rule(function (\Filament\Forms\Get $get) {
                        $returnDate = $get('return_date');
                        return $returnDate ? 'after_or_equal:' . $returnDate : null;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                Tables\Columns\TextColumn::make('return_date')
                    ->label("Return Date")
                    ->date()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Return Date copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('actual_return')
                    ->label("Actual Return")
                    ->date()
                    ->sortable()
                    ->placeholder("Not Yet Returned")
                    ->copyable()
                    ->copyMessage('Actual Return copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('late_fee')
                    ->label("Late Fee")
                    ->numeric()
                    ->sortable()
                    ->money('IDR')
                    ->copyable()
                    ->copyMessage('Late Fee copied')
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
            ])->defaultSort('created_at', 'desc')
            ->filters([
                TrashedFilter::make()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(LoanExporter::class)
                    ->maxRows(100000)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReturns::route('/'),
            'create' => Pages\CreateReturn::route('/create'),
            'edit' => Pages\EditReturn::route('/{record}/edit'),
        ];
    }
}
