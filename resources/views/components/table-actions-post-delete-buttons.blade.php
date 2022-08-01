<div>
    <br>

    <pre>{{print_r($selectedItems)}}</pre>

    @if($isPendingTable)
        <x-label-segmented class="mb-4" color="yellow" title="Selected Total" value="{{number_format($selectedTotal, 2)}}"/>
    @endif

    <div class="flex">
        <div class="flex items-center mr-8 px-4 rounded border border-gray-300 bg-gray-200">
            <input id="inline-checkbox"
                   type="checkbox"
                   class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:outline-none">

            <label for="inline-checkbox" class="ml-2 text-sm font-medium text-gray-900 uppercase">
                Select All
            </label>
        </div>

        @if($isPendingTable)
            <div class="flex items-center mr-2">
                <x-jet-button
                    :disabled="!$selectedItems"
                    wire:loading.attr="disabled"
                    wire:click="uploadSelected"
                    class="bg-green-700 hover:bg-green-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>

                    {{ __('Upload') }}
                </x-jet-button>
            </div>
        @endif

        <div class="flex items-center mr-4">
            <x-jet-danger-button
                :disabled="!$selectedItems"
                wire:loading.attr="disabled"
                wire:click="deleteSelected"
                class="bg-red-700 hover:bg-red-800">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>

                {{ __('Delete') }}
            </x-jet-danger-button>
        </div>
    </div>


</div>
