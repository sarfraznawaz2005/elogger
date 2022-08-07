<div wire:init="load">

    <x-status-modal wire:model="loading">
        Please wait while we are fetching user data...
    </x-status-modal>

    @if (!$loading)
        <div wire:ignore>
            <x-panel title="Users">
                <livewire:data-tables.users-data-table/>
            </x-panel>
        </div>
    @endif

</div>