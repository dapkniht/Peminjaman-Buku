<?php

namespace App\Filament\Resources\AuthorResource\Pages;

use App\Filament\Resources\AuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAuthor extends CreateRecord
{
    protected static string $resource = AuthorResource::class;

    protected function getRedirectUrl(): string
        {
            return $this->getResource()::getUrl('index');
        }

    protected function mutateFormDataBeforeCreate(array $data): array
        {
           

            if(!$data['photo']) $data['photo'] = "author/default.jpeg";
        
            return $data;
        }
}
