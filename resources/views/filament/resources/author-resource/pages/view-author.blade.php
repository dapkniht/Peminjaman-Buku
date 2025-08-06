<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Profil Author --}}
        <x-filament::section>
            <div class="flex flex-col md:flex-row items-center gap-6">
                {{-- Photo --}}
                <x-filament::avatar
                    src="{{ asset('storage/'.$record->photo) }}"
                    alt="{{ $record->name }}"
                    size="w-20 h-20"
                />

                {{-- Information --}}
                <div class="space-y-2 w-full">
                    <h2 class="text-2xl font-bold">{{ $record->name }}</h2>
                    <p class="text-sm text-gray-400">{{ $record->bio }}</p>
                </div>
            </div>
        </x-filament::section>

        {{-- Book Table --}}
        <x-filament::section>
            <x-slot name="heading">
                Books by {{ $record->name }}
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
