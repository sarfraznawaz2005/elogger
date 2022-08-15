<div>
    <x-jet-button wire:loading.attr="disabled" wire:click="openModal"
                  class="bg-blue-700 hover:bg-blue-800">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>

        {{ __('Replicate') }}
    </x-jet-button>

    <x-jet-dialog-modal wire:model="isModalOpen">

        <x-slot name="title">
            Confirm To Replicate All Pending Entries
        </x-slot>

        <x-slot name="content">
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="replicateMessage"
                             value="{{ __('Optional: Replicated stories description ? Leave emtpy to auto-retrive.') }}"/>
                <x-jet-input id="replicateMessage" type="text" class="mt-1 block w-full"
                             wire:model.defer="replicateMessage"/>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-button wire:click="replicate" wire:loading.attr="disabled" class="mr-2 bg-blue-700 hover:bg-blue-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>

                {{ __('Replicate')  }}
            </x-jet-button>

            <x-jet-button x-on:click="show = false" wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Cancel')  }}
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
