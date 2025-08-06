<?php

namespace App\Filament\Resources;

use App\Enums\LoanStatus;
use App\Filament\Exports\LoanExporter;
use App\Filament\Imports\LoanImporter;
use App\Filament\Resources\LoanResource\Pages;
use App\Filament\Resources\LoanResource\RelationManagers;
use App\Models\Loan;
use App\Models\Setting;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;

class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Transactions';
    protected static bool $shouldSkipAuthorization = true;
    protected static ?int $navigationSort = 4;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('Member')
                    ->required()
                    ->searchable()
                    ->relationship(name: 'user', titleAttribute: 'name', modifyQueryUsing: fn(Builder $query) => $query->whereHas('roles', fn($q) => $q->where('name', 'member')),)
                    ->preload(),
                Forms\Components\Select::make('book_id')
                    ->label('Book')
                    ->required()
                    ->searchable()
                    ->relationship(name: 'book', titleAttribute: 'title')
                    ->preload(),
                Forms\Components\DatePicker::make('borrow_date')
                    ->required()
                    ->default(Carbon::now()),
                Forms\Components\DatePicker::make('return_date')
                    ->required()
                    ->default(fn(?Setting $record) => Carbon::now()->addDays($record?->loan_duration_days ?? 7)),
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
                TrashedFilter::make(),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('borrow_date_start')
                            ->label('Borrow Date Start')
                            ->default(now()),
                        DatePicker::make('borrow_date_end')
                            ->label('Borrow Date End'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['borrow_date_start'],
                                fn(Builder $query, $date): Builder => $query->whereDate('borrow_date', '>=', $date),
                            )
                            ->when(
                                $data['borrow_date_end'],
                                fn(Builder $query, $date): Builder => $query->whereDate('borrow_date', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(LoanImporter::class),
                ExportAction::make()
                    ->exporter(LoanExporter::class)
                    ->maxRows(100000)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);;
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
            'index' => Pages\ListLoans::route('/'),
            'create' => Pages\CreateLoan::route('/create'),
            'edit' => Pages\EditLoan::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
