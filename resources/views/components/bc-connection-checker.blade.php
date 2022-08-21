@if(session()->has('not_connected'))
    <div class="w-auto inline-block flex justify-center items-center mb-4" wire:ignore x-data>
        <div class="p-3 text-sm text-white break-words flex items-center justify-center text-center rounded-lg red-box">
            <div>
                <div class="uppercase font-bold mb-4">
                    Your Data Might Be Off !
                </div>

                <p class="mb-2">We are unable to communicate with Basecamp API, make sure you are connected to internet & your settings are correct.</p>
                <p class="mb-4">You can try again using button below.</p>

                <div class="inline" x-data="{tooltip: 'Refresh Data'}">
                    <x-jet-button x-on:click="window.livewire.emit('refreshClicked')" wire:loading.attr="disabled" x-tooltip="tooltip" class="bg-gray-700 px-2.5 hover:bg-blue-600 mr-2 border-0">
                        <x-icons.refresh/> {{ __('Check Now') }}
                    </x-jet-button>
                </div>

            </div>
        </div>
    </div>
@endif
