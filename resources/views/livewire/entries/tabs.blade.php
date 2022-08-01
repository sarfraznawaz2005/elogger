<div x-data="{tab: 1}" class="bg-white">
    <div class="p-4">
        <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200">
            <li class="mr-2">
                <a href="!#0" @click.prevent="tab = 1"
                   :class="{'text-blue-600 hover:text-blue-800 bg-gray-100 active': tab === 1, 'hover:text-gray-600 hover:bg-gray-100': tab !== 1}"
                   class="inline-flex font-bold p-4 rounded-t-lg border-l border-t border-r border-gray-200"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" :class="{'text-blue-600 hover:text-blue-800': tab === 1, 'text-gray-400': tab !== 1}" class="mr-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Pending
                </a>
            </li>
            <li class="mr-2">
                <a href="!#0" @click.prevent="tab = 2"
                   :class="{'text-blue-600 hover:text-blue-800 bg-gray-100 active': tab === 2, 'hover:text-gray-600 hover:bg-gray-100': tab !== 2}"
                   class="inline-flex font-bold p-4 rounded-t-lg border-l border-t border-r border-gray-200">
                    <svg xmlns="http://www.w3.org/2000/svg" :class="{'text-blue-600 hover:text-blue-800': tab === 2, 'text-gray-400': tab !== 2}" class="mr-2 w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Posted
                </a>
            </li>
        </ul>
    </div>

    <div id="tabContent">
        <div x-show="tab === 1" class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-500">
                <livewire:data-tables.pending-entries-data-table />
            </p>
        </div>

        <div x-show="tab === 2" class="p-4 bg-gray-50 rounded-lg">
            <p class="text-sm text-gray-500">
                Posted
            </p>
        </div>
    </div>

</div>

