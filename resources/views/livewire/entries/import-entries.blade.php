<div>

    <x-status-modal wire:model="loading">
        {{$loadingMessage}}
    </x-status-modal>

    <x-jet-button
        wire:click="import"
        wire:loading.attr="disabled"
        class="bg-blue-700 hover:bg-blue-800 ml-4">
        <x-icons.upload/> {{ __('Import Entries') }}
    </x-jet-button>

    <x-jet-dialog-modal wire:model="isModalOpen">

        <x-slot name="title">
            {{$modalTitle}}
        </x-slot>

        <x-slot name="content">
            <div class="my-4">
                <div class="flex items-center mt-1">
                    <input type="file" wire:model="csvFile" class="mb-4">
                </div>
                <x-jet-input-error for="csvFile" class="mt-2"/>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="flex justify-between w-full">
                <x-jet-button wire:click="downloadSample" class="w-32">
                    {{ __('Download Sample') }}
                </x-jet-button>

                <div class="flex items-center">
                    <x-jet-button
                        wire:click="save"
                        wire:loading.attr="disabled"
                        class="mr-2 bg-blue-700 hover:bg-blue-800 w-32">

                        <x-icons.ok/> {{ __('Import')  }}
                    </x-jet-button>

                    <x-jet-button x-on:click="show = false" wire:click="closeModal" wire:loading.attr="disabled" class="w-32">
                        {{ __('Close')  }}
                    </x-jet-button>
                </div>
            </div>
        </x-slot>

    </x-jet-dialog-modal>
</div>
