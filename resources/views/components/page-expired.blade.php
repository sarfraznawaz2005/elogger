<div
    wire:ignore
    x-data="{expired:false}"
    x-init="Livewire.onPageExpired(() => expired = true)"
>
    <div style="display: none;" x-show="expired" class="fixed z-50 bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center">

        <div x-show="expired">
            <div class="fixed inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div x-show="expired"
             x-on:click.outside="expired = false"
             x-on:keydown.window.escape="expired = false"
             class="relative bg-gray-100 rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full sm:p-6">

            <div x-show="expired" class="w-full text-center">
                <p class="font-bold text-red-600 mb-4">This Page is Expired</p>
                <p class="mb-4">Click the button below to refresh the page.</p>

                <x-jet-button x-on:click="window.location.reload()" wire:loading.attr="disabled" class="bg-blue-600 px-2.5 hover:bg-blue-800 border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>

                    {{ __('Refresh Page') }}
                </x-jet-button>
            </div>

        </div>

    </div>

</div>


