<div>
    <br>

    @if($isPendingTable && !$this->results->isEmpty())

        @push('js')
            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('checkboxes', () => ({

                        checkedValues: '',
                        selectedTotal: '0.00',
                        selectAll: null,
                        checkboxes: null,
                        uploadedButton: null,
                        deleteButton: null,

                        init() {

                            this.selectAll = this.$refs.selectAll;
                            this.uploadedButton = this.$refs.uploadedButton;
                            this.deleteButton = this.$refs.deleteButton;

                            // set events on initial checkbox items
                            document.addEventListener('DOMContentLoaded', () => {
                                this.scanCheckboxes();
                                this.setCheckedValues();
                            });

                            window.livewire.on('refreshLivewireDatatable', () => {
                                // uncheck all on table refresh
                                this.reset();

                                // re-scan for checkboxes
                                window.livewire.hook('message.processed', () => {
                                    this.scanCheckboxes();

                                    // set events on any newly added items
                                    this.setCheckedValues();
                                });
                            });

                        },

                        scanCheckboxes() {
                            this.checkboxes = document.querySelectorAll('#pendingTable .check-entry');
                        },

                        setCheckedValues() {

                            this.selectAll.addEventListener('change', (e) => {
                                this.checkboxes.forEach(checkbox => {
                                    checkbox.checked = e.target.checked;
                                    checkbox.dispatchEvent(new Event('change'));
                                });
                            }, false);

                            this.checkboxes.forEach(checkbox => {
                                checkbox.addEventListener('change', () => {
                                    this.checkedValues = Array.from(this.checkboxes)
                                        .filter(checkbox => checkbox.checked)
                                        .map(checkbox => checkbox.value)
                                        .toString();

                                    this.selectedTotal = Array.from(this.checkboxes)
                                        .filter(checkbox => checkbox.checked)
                                        .map(checkbox => checkbox.parentNode.parentNode.querySelector('.hours').innerText.replace('\s/g', ''))
                                        .reduce((a, b) => parseFloat(a) + parseFloat(b), 0)
                                        .toFixed(2);

                                    //console.log(`checked: ${this.checkedValues}`);

                                    // feels good
                                    setTimeout(() => {
                                        this.uploadedButton.disabled = this.deleteButton.disabled = !this.checkedValues.length > 0;
                                    }, 200);

                                }, false);
                            });
                        },

                        reset() {
                            this.uploadedButton.disabled = this.deleteButton.disabled = true;

                            this.selectAll.checked = false;

                            this.checkboxes.forEach(checkbox => {
                                checkbox.checked = false;
                                checkbox.dispatchEvent(new Event('change'));
                            });
                        }

                    }));
                });
            </script>
        @endpush

        <div class="flex w-full justify-between" x-data="checkboxes">

            <div class="md:flex items-center justify-start" wire:ignore>
                <div class="flex items-center mr-2 py-2 px-4 rounded border border-gray-300 bg-white justify-between">

                    <input x-ref="selectAll" id="select-all-checkbox"
                           type="checkbox"
                           class="w-4 h-4 focus:outline-none">

                    <label for="select-all-checkbox" class="ml-2 text-sm font-medium text-gray-900 select-none">
                        Select All
                    </label>
                </div>

                <div
                    class="inline-flex items-center pl-2 text-sm font-semibold text-center bg-yellow-200 border-yellow-500 mr-2 text-gray-900 rounded">
                    Selected Total
                    <div
                        x-text="selectedTotal"
                        class="inline-flex justify-center items-center ml-1 p-2 text-gray-900 text-sm font-bold bg-yellow-300 rounded-r">
                        0.00
                    </div>
                </div>
            </div>

            <div class="md:flex items-center justify-end">
                <div class="md:flex items-center md:mr-2">
                    <x-jet-button
                        disabled
                        x-ref="uploadedButton"
                        x-on:click="sendBrowserEvent('confirm', 'onUploadSelected', checkedValues, 'Are you sure you want to upload selected entries ?')"
                        wire:loading.attr="disabled"
                        class="bg-green-600 ml-4 hover:bg-green-700">

                        <x-icons.upload/>

                        {{ __('Upload Selected') }}
                    </x-jet-button>
                </div>

                <div class="flex items-center">
                    <x-jet-danger-button
                        disabled
                        x-ref="deleteButton"
                        x-on:click="sendBrowserEvent('confirm', 'deleteSelected', checkedValues, 'Are you sure you want to delete selected entries ?')"
                        wire:loading.attr="disabled"
                        class="bg-red-700 hover:bg-red-800">

                        <x-icons.delete class="mr-1"/>

                        {{ __('Delete Selected') }}
                    </x-jet-danger-button>
                </div>
            </div>

        </div>
    @endif

    @if(!$isPendingTable && !$this->results->isEmpty())
        <div class="flex items-center justify-end w-full">
            <x-jet-danger-button
                wire:ignore
                x-on:click="sendBrowserEvent('confirm', 'deleteAllPosted', null, 'Are you sure you want to delete all posted entries ?')"
                wire:loading.attr="disabled"
                class="bg-red-700 hover:bg-red-800">

                <x-icons.delete class="mr-1"/>  {{ __('Delete All') }}
            </x-jet-danger-button>
        </div>
    @endif

</div>
