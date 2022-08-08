<div x-data="{tab: 1}" class="bg-gray-50 rounded-lg shadow-md">
    <div class="p-6 pb-0">
        <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500">
            <li class="mr-2">
                <a href="!#0" @click.prevent="tab = 1"
                   :class="{'text-gray-600 bg-gray-200 active': tab === 1, 'text-gray-500 bg-gray-100 hover:bg-gray-200': tab !== 1}"
                   class="inline-flex font-bold p-3 rounded-t-lg"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" :class="{'text-gray-600': tab === 1, 'text-gray-400': tab !== 1}" class="mr-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Pending Entries
                </a>
            </li>
            <li class="mr-2">
                <a href="!#0" @click.prevent="tab = 2"
                   :class="{'text-gray-600 bg-gray-200 active': tab === 2, 'text-gray-500 bg-gray-100 hover:bg-gray-200': tab !== 2}"
                   class="inline-flex font-bold p-3 rounded-t-lg"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" :class="{'text-gray-600': tab === 2, 'text-gray-400': tab !== 2}" class="mr-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Uploaded Entries
                </a>
            </li>
        </ul>
    </div>

    <div id="tabContent">
        <div x-show="tab === 1" id="pendingTable" class="px-6 pb-2.5 animate-in fade-in duration-500">
            <div class="max-w-7xl mx-auto p-0 border-0 m-0 mb-4">
                <div class="p-6 bg-gray-200 text-gray-800 rounded-b-lg rounded-r-lg">
                    <livewire:data-tables.pending-entries-data-table />
                </div>
            </div>
        </div>

        <div x-show="tab === 2" id="postedTable" class="px-6 pb-2.5 animate-in fade-in duration-500">
            <div class="max-w-7xl mx-auto p-0 border-0 m-0 mb-4">
                <div class="p-6 bg-gray-200 text-gray-800 rounded-b-lg rounded-r-lg">
                    <livewire:data-tables.posted-entries-data-table />
                </div>
            </div>
        </div>
    </div>

</div>

