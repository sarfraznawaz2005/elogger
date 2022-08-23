<div x-data="{tab: 1}">
    <div class="pb-0">
        <ul class="flex text-sm md:font-medium text-center text-gray-500">
            <li class="mr-1">
                <a href="!#0" @click.prevent="tab = 1"
                   :class="{'text-gray-600 bg-gray-200 active': tab === 1, 'text-gray-500 bg-gray-100 hover:bg-gray-200': tab !== 1}"
                   class="inline-flex font-bold py-3 px-5 rounded-t-lg"
                >

                    <x-icons.time/> Pending Entries
                </a>
            </li>
            <li class="mr-1">
                <a href="!#0" @click.prevent="tab = 2"
                   :class="{'text-gray-600 bg-gray-200 active': tab === 2, 'text-gray-500 bg-gray-100 hover:bg-gray-200': tab !== 2}"
                   class="inline-flex font-bold py-3 px-5 rounded-t-lg"
                >
                    <x-icons.ok2/> Uploaded Entries
                </a>
            </li>
        </ul>
    </div>

    <div id="tabContent">
        <div x-show="tab === 1" id="pendingTable" wire:key="table-table-pending" x-transition.duration>
            <div class="mx-auto">
                <div class="p-5 bg-gray-200 text-gray-800 rounded-b-lg rounded-r-lg">
                    <livewire:data-tables.pending-entries-data-table  />
                </div>
            </div>
        </div>

        <div x-show="tab === 2" id="postedTable" wire:key="table-table-posted" x-cloak x-transition.duration>
            <div class="mx-auto">
                <div class="p-5 bg-gray-200 text-gray-800 rounded-b-lg rounded-r-lg">
                    <livewire:data-tables.posted-entries-data-table />
                </div>
            </div>
        </div>
    </div>

</div>

