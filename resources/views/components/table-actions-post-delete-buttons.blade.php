<div>
    <br>

    @if($isPendingTable && !$this->results->isEmpty())

        <div class="flex w-full justify-between">

            <div class="md:flex items-center justify-start" wire:ignore>
                <div class="flex items-center mr-2 py-2 px-4 rounded border border-gray-300 bg-white justify-between"
                     x-data="{checked:false}"
                     x-init="window.livewire.on('refreshLivewireDatatable', () => checked = false)"
                >

                    <input id="select-all-checkbox"
                           type="checkbox"
                           x-model="checked"
                           class="w-4 h-4 focus:outline-none">

                    <label for="select-all-checkbox"
                           class="ml-2 text-sm font-medium text-gray-900 select-none">
                        Select All
                    </label>
                </div>

                <div
                    class="inline-flex items-center pl-2 text-sm font-semibold text-center bg-yellow-200 border-yellow-500 mr-2">
                    Selected Total
                    <div
                        id="selectedTotal"
                        class="inline-flex justify-center items-center ml-1 p-2 text-gray-800 text-sm font-bold bg-yellow-400">
                        0.00
                    </div>
                </div>
            </div>

            <div class="md:flex items-center justify-end">
                <div class="md:flex items-center md:mr-2">
                    <div class="inline" x-data="{ open: false, working: false }" x-cloak>

                        <x-jet-button
                            disabled
                            id="uploadSelectedButton"
                            x-on:click="open = true; working = false"
                            wire:loading.attr="disabled"
                            class="bg-green-700 hover:bg-green-800">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>

                            {{ __('Upload Selected') }}
                        </x-jet-button>
                        @include('components.confirm', ['value' => $isPendingTable, 'function' => 'onUploadSelected', 'title' => 'Are you sure you want to upload selected entries ?'])
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="inline" x-data="{ open: false, working: false }" x-cloak>

                        <x-jet-danger-button
                            disabled
                            id="deleteSelectedButton"
                            x-on:click="open = true; working = false"
                            wire:loading.attr="disabled"
                            class="bg-red-700 hover:bg-red-800">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>

                            {{ __('Delete Selected') }}
                        </x-jet-danger-button>

                        @include('components.confirm', ['value' => $isPendingTable, 'function' => 'deleteSelected', 'title' => 'Are you sure you want to delete selected entries ?'])
                    </div>
                </div>
            </div>

        </div>

        @push('js')
            <script>

                let checkedValues = '';
                let checkboxes = document.querySelectorAll('#pendingTable .check-entry');
                let elSelectedTotal = document.querySelector('#selectedTotal');
                let uploadedButton = document.querySelector('#uploadSelectedButton');
                let deleteButton = document.querySelector('#deleteSelectedButton');

                // set events on initial checkbox items
                document.addEventListener('DOMContentLoaded', setCheckedValues);

                window.livewire.on('refreshLivewireDatatable', () => {
                    // uncheck all on table refresh
                    reset();

                    // re-scan for checkboxes
                    window.livewire.hook('message.processed', () => {
                        checkboxes = document.querySelectorAll('#pendingTable .check-entry');

                        // set events on any newly added items
                        setCheckedValues();
                    });
                });

                function setCheckedValues() {

                    document.querySelector('#select-all-checkbox').addEventListener('change', (e) => {
                        checkboxes.forEach(checkbox => {
                            checkbox.checked = e.target.checked;
                            checkbox.dispatchEvent(new Event('change'));
                        });
                    }, false);

                    checkboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', () => {
                            checkedValues = Array.from(checkboxes)
                                .filter(checkbox => checkbox.checked)
                                .map(checkbox => checkbox.value)
                                .toString();

                            elSelectedTotal.innerHTML = Array.from(checkboxes).filter(checkbox => checkbox.checked)
                                .map(checkbox => checkbox.parentNode.parentNode.parentNode.querySelector('.hours').innerText.replace('\s/g', ''))
                                .reduce((a, b) => parseFloat(a) + parseFloat(b), 0)
                                .toFixed(2);

                            //console.log(`checked: ${checkedValues}`);

                            // finally sent event to set values accordingly
                            window.livewire.emitTo('entries.entry', 'setSelectedItems', checkedValues);

                            // enable buttons later so that above event sets ids first to Entry component
                            setTimeout(() => {
                                uploadedButton.disabled = deleteButton.disabled = !checkedValues.length > 0;
                            }, 200);

                        }, false);
                    });
                }

                function reset() {
                    uploadedButton.disabled = deleteButton.disabled = true;

                    document.querySelector('#select-all-checkbox').checked = false;

                    checkboxes.forEach(checkbox => {
                        checkbox.checked = false;
                        checkbox.dispatchEvent(new Event('change'));
                    });
                }
            </script>
        @endpush

    @endif

    @if(!$isPendingTable && !$this->results->isEmpty())
        <div class="flex items-center justify-end w-full">

            <div class="inline" x-data="{ open: false, working: false }" x-cloak>

                <x-jet-danger-button
                    x-on:click="open = true; working = false"
                    wire:loading.attr="disabled"
                    class="bg-red-700 hover:bg-red-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>

                    {{ __('Delete All') }}
                </x-jet-danger-button>

                @include('components.confirm', ['value' => $isPendingTable, 'function' => 'deleteAllPosted', 'title' => 'Are you sure you want to delete all posted entries ?'])
            </div>

        </div>
    @endif

</div>
