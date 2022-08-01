<div>

    <div class="p-2 bg-white w-full rounded-lg overflow-hidden shadow-md my-4">
        <div class="text-center">
            <x-jet-button wire:loading.attr="disabled" wire:click="refreshClicked" class="bg-blue-700 hover:bg-blue-800 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>

                {{ __('Refresh') }}
            </x-jet-button>
        </div>
    </div>

    <x-status-modal wire:model="loading">
        Please wait while we are refreshing data...
    </x-status-modal>

</div>
