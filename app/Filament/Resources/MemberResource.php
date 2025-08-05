<?php

namespace App\Filament\Resources;

use App\Filament\Exports\MemberExporter;
use App\Filament\Imports\MemberImporter;
use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $modelLabel = 'Member';
    protected static ?string $pluralModelLabel = 'Members';
    protected static ?string $navigationLabel = 'Members';
    protected static ?string $navigationGroup = 'Users';
    protected static bool $shouldSkipAuthorization = true;



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                TextInput::make('email')
                    ->unique(table: User::class)
                    ->required()
                    ->email()
                    ->maxLength(100),
                TextInput::make('nisn')
                    ->unique(table: User::class)
                    ->required()
                    ->maxLength(20),
                TextInput::make('kelas')
                    ->required()
                    ->maxLength(100),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->maxLength(255)
                    ->dehydrated(fn($state) => filled($state))
                    ->required(fn(string $context) => $context === 'create')
                    ->afterStateHydrated(fn(TextInput $component, $state) => $component->state('')),

                TextInput::make('password_confirmation')
                    ->label('Password Confirmation')
                    ->password()
                    ->revealable()
                    ->same('password')
                    ->dehydrated(false)
                    ->required(fn(string $context) => $context === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->copyable()
                    ->copyMessage('Id copied')
                    ->copyMessageDuration(1500),
                TextColumn::make('name')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Name')
                    ->copyMessageDuration(1500),
                TextColumn::make('email')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Email copied')
                    ->copyMessageDuration(1500),
                TextColumn::make('nisn')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Nisn copied')
                    ->copyMessageDuration(1500),
                TextColumn::make('kelas')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Kelas copied')
                    ->copyMessageDuration(1500),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(MemberImporter::class),
                ExportAction::make()
                    ->exporter(MemberExporter::class)
                    ->maxRows(100000)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(
                fn(Builder $query) =>
                $query->whereHas('roles', fn($q) => $q->where('name', 'member'))
            );
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
