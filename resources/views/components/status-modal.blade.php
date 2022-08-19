<div class="inline" x-data="{ open: @entangle($attributes->wire('model')), working: false }" x-cloak>

    <div x-show="open"
         class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center" style="z-index: 9999999999;">
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 transition-opacity">
            <div class="absolute inset-0 bg-gray-500 opacity-50"></div>
        </div>

        <div x-show="open" x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-gray-100 rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-lg">

            <div class="w-auto">
                <span class="py-4 px-5 mr-2 text-lg text-white font-bold bg-blue-700 rounded-lg border inline-flex items-center w-full">
                    <x-icons.spinner/>

                    {{ $slot }}
                </span>
            </div>
        </div>
    </div>
</div>
