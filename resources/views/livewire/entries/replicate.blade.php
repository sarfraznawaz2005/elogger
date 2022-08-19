<div>
    <x-jet-button wire:loading.attr="disabled" wire:click="openModal" class="bg-blue-700 hover:bg-blue-800">
        <x-icons.refresh/> {{ __('Replicate') }}
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
                <x-icons.ok/> {{ __('Replicate')  }}
            </x-jet-button>

            <x-jet-button x-on:click="show = false" wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Cancel')  }}
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
