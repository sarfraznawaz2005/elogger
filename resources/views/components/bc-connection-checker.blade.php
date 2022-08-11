@if(session()->has('not_connected'))
    <div class="w-auto inline-block flex justify-center items-center mb-4" x-data="{}">
        <div class="p-3 text-sm text-white break-words flex items-center justify-center text-center rounded-lg bg-red-400">
            <div>
                <div class="uppercase font-bold mb-4">
                    Your Data Might Be Off !
                </div>

                <p class="mb-2">We are unable to communicate with Basecamp API, make sure you are connected to internet & your settings are correct.</p>
                <p class="mb-4">You can try again using button below.</p>

                <div class="inline" x-data="{tooltip: 'Refresh Data'}">
                    <x-jet-button x-on:click="window.livewire.emit('refreshClicked')" wire:loading.attr="disabled" x-tooltip="tooltip" class="bg-gray-700 px-2.5 hover:bg-blue-600 mr-2 border-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>

                        {{ __('Check Now') }}
                    </x-jet-button>
                </div>

            </div>
        </div>
    </div>
@endif
