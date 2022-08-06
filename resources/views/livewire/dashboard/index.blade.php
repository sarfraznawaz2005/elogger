<div>
    <x-panel title="Dashboard">
        <div wire:ignore>
            @include('livewire.dashboard.stats')
            @include('livewire.dashboard.graph')
        </div>
    </x-panel>
</div>
