<div>
    @livewire('edit-profile')
<x-filament-panels::page>

    <form wire:submit="create">
        {{ $this->form }}
        
        <x-filament::button type="submit" style="margin-top: 20px">
            Save
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</x-filament-panels::page>

</div>