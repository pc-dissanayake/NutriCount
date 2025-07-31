@php use Filament\Facades\Filament; @endphp

<x-filament::page>
    <form wire:submit.prevent="save" class="space-y-6 max-w-xl mx-auto mt-8">
        {{ $this->form }}
        <x-filament::button type="submit" color="primary">
            Save Diet Entry
        </x-filament::button>
    </form>
</x-filament::page>
