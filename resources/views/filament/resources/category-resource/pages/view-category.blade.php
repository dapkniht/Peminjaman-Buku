<x-filament-panels::page>
    <div class="space-y-6">

        {{-- Book Table --}}
        <x-filament::section>
            <x-slot name="heading">
                Books by {{ $record->name }} Category
            </x-slot>

            {{ $this->table }}
        </x-filament::section>

        {{-- Relation Manager (opsional) --}}
        @if (count($relationManagers = $this->getRelationManagers()))
            <x-filament-panels::resources.relation-managers
                :active-manager="$this->activeRelationManager"
                :managers="$relationManagers"
                :owner-record="$record"
                :page-class="static::class"
            />
        @endif
    </div>
</x-filament-panels::page>
