<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;

class Profile extends Page implements HasForms
{
    use InteractsWithForms;
    public ?array $data = [];

    protected static ?string $navigationIcon = 'hugeicons-user-settings-01';

    protected static string $view = 'filament.pages.profile';
    protected static ?string $navigationGroup = 'Settings';

    public function mount(): void
    {
        $this->form->fill(auth()->user()->toArray());
    }

    public  function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(100),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->disabled()
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
            ])
            ->statePath('data');
    }

    public function create(): void
    {
        $user = auth()->user();
        $data = $this->form->getState();

        $updateData = [
            'name' => $data['name'],
        ];

        if (array_key_exists('password', $data) && filled($data['password'])) {
            $updateData['password'] = bcrypt($data['password']);
        }

        $user->update($updateData);

        Notification::make()
            ->title('Profile Updated')
            ->success()
            ->send();
    }
}
