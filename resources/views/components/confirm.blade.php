<div x-show="open" class="fixed z-50 bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center">

    <div x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 transition-opacity">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <div x-show="open"
         x-on:click.outside="open = false"
         x-on:keydown.window.escape="open = false"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
         class="relative bg-gray-100 rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full sm:p-6">

        <div class="w-full">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ __($title ?? 'Are you sure you want to delete ?') }}
                </h3>
                <div class="mt-2">
                    <div class="mt-10 flex justify-center">
                        <x-jet-danger-button class="ml-3 mr-8" wire:click="$emit('{{$function}}', {{$value}})"
                                             x-on:click="open = false" x-bind:disabled="!open">
                            {{ __('Confirm') }}
                        </x-jet-danger-button>

                        <x-jet-button x-on:click="open = false">
                            {{ __('Cancel') }}
                        </x-jet-button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

