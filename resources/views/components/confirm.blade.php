@push('js')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('confirm', () => ({
                open: false,
                event: null,
                value: null,
                title: 'Are you sure you want to delete ?',

                init() {
                    document.addEventListener('confirm', event => {
                        this.title = event.detail.title ? event.detail.title : this.title;
                        this.event = event.detail.event;
                        this.value = event.detail.value;

                        this.open = true;
                    });
                }
            }))
        });
    </script>
@endpush

<div x-data="confirm"
     x-show="open"
     wire:ignore
     x-cloak
     class="fixed z-50 bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center">

    <div x-show="open" class="fixed inset-0 transition-opacity">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <div x-show="open"
         x-on:click.outside="open = false"
         x-on:keydown.window.escape="open = false"
         x-transition.duration
         class="relative bg-gray-100 rounded-lg pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">

        <div class="w-auto">
            <div class="text-center">
                <h3 class="text-lg leading-4 font-medium text-gray-900" x-text="title"></h3>
                <div class="mt-10 flex justify-center">
                    <x-jet-danger-button
                        class="w-32 justify-center mr-8"
                        x-on:click="open = false; window.livewire.emit(event, value)"
                        x-bind:disabled="!open">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>

                        {{ __('Confirm') }}
                    </x-jet-danger-button>

                    <x-jet-button x-on:click="open = false" class="w-32 justify-center">
                        {{ __('Cancel') }}
                    </x-jet-button>
                </div>
            </div>
        </div>

    </div>
</div>

