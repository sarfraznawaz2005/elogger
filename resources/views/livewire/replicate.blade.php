<div>
    <x-jet-button wire:loading.attr="disabled" wire:click="openModal"
                  class="bg-blue-700 hover:bg-blue-800 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>

        {{ __('Replicate') }}
    </x-jet-button>

    <x-jet-dialog-modal wire:model="isModalOpen">

        <x-slot name="title">
            Are you sure to replicate ?
        </x-slot>

        <x-slot name="content">
            <div class="col-span-6 sm:col-span-4">
                <x-jet-label for="replicateMessage"
                             value="{{ __('(Optional: Replicated stories description ? Leave emtpy to auto-retrive.)') }}"/>
                <x-jet-input id="replicateMessage" type="text" class="mt-1 block w-full"
                             wire:model.defer="replicateMessage"/>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-button wire:click="replicate" wire:loading.attr="disabled"
                          class="mr-2 bg-blue-700 hover:bg-blue-800 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                {{ __('Replicate')  }}
            </x-jet-button>

            <x-jet-button wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Cancel')  }}
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
