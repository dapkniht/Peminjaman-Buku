<?php

namespace App\Filament\Resources\ReturnResource\Pages;

use App\Filament\Resources\ReturnResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateReturn extends CreateRecord
{
    protected static string $resource = ReturnResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {


    //     if (!empty($data['password'])) {
    //         $data['password'] = Hash::make($data['password']);
    //     }
    //     return $data;
    // }

    // protected function handleRecordCreation(array $data): Model
    // {
    //     $loan = static::getModel()::($data);
    //     $loan->assignRole("admin");

    //     return $loan;
    // }
}
