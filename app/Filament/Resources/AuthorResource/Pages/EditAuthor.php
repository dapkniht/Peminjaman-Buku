<?php

namespace App\Filament\Resources\AuthorResource\Pages;

use App\Filament\Resources\AuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAuthor extends EditRecord
{
    protected static string $resource = AuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
        {
            return $this->getResource()::getUrl('index');
        }

    protected function mutateFormDataBeforeSave(array $data): array
        {
           

            if(!$data['photo']) $data['photo'] = "author/default.jpeg";
        
            return $data;
        }
}
