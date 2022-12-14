@if (user()->isAdmin())
    <div wire:init="load">

        <div class="flex flex-row justify-center items-center mb-4 mx-auto" wire:offline wire:key="offline-users" style="margin-left: 28%;">
            <div class="p-3 text-sm break-words flex items-center rounded-lg yellow-box">
                <div class="flex items-center justify-center text-center mx-auto">
                    <x-icons.info/>
                    <p class="font-bold text-sm text-gray-800 break-words">
                        It seems you are offline. Uploaded Hours and Month Projection might show up wrong !
                    </p>
                </div>
            </div>
        </div>

        <x-status-modal wire:model="loading">
            Please wait while we are fetching user data...
        </x-status-modal>

        @if (!$loading)
            <div wire:ignore>
                <x-panel title="Users (Data This Month)">
                    <div class="bg-gray-200 rounded-lg p-6 pt-4">
                        <livewire:data-tables.users-data-table/>
                    </div>
                </x-panel>
            </div>
        @endif

    </div>
@endif
