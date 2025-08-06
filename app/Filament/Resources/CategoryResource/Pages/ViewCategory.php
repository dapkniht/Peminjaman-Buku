<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Exports\CategoryExporter;
use App\Filament\Imports\CategoryImporter;
use App\Filament\Resources\AuthorResource;
use App\Filament\Resources\CategoryResource;
use App\Models\Book;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ViewCategory extends ViewRecord implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = CategoryResource::class;
    protected static string $view = 'filament.resources.category-resource.pages.view-category';



    public function table(Table $table): Table

    {
        return $table
            ->relationship(fn(): HasMany => $this->record->books())
            ->inverseRelationship('books')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->copyable()
                    ->copyMessage('Id copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\ImageColumn::make('cover_url')
                    ->label("Cover")
                    ->width(50)
                    ->height(50),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Title copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Category copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('author.name')
                    ->searchable()
                    ->url(fn(Book $record): string => AuthorResource::getUrl('view', ['record' => $record->author_id])),
                Tables\Columns\TextColumn::make('isbn')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Isbn copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Stock copied')
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
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(CategoryImporter::class),
                ExportAction::make()
                    ->exporter(CategoryExporter::class)
                    ->maxRows(100000),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }
}
