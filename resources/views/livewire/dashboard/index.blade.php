<div>
    <x-panel title="Dashboard">
        <div wire:ignore>
            @include('livewire.dashboard.stats')
        </div>
        @include('livewire.dashboard.graph')
    </x-panel>
</div>
