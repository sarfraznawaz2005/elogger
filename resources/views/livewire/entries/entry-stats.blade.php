<div class="mb-12" wire:ignore>

    <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-6 w-full">

        <div class="flex items-center relative p-3 w-full bg-gray-200 rounded shadow-md">
            <div class="w-full text-center">
                <div class="px-4 sm:px-0">
                    <h3 class="text-sm font-medium text-gray-900 sm:text-lg">Pending Hours Today</h3>

                    <p class="mt-2 text-sm text-gray-600">
                        <span
                            class="bg-green-500 text-white md:text-lg sm:text-xs font-bold md:mr-2 px-2.5 py-1 rounded-md sm:w-24 inline-block shadow-lg">
                                {{$PendingTodosHoursToday}}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center relative p-3 w-full bg-gray-200 rounded shadow-md">
            <div class="w-full text-center">
                <div class="px-4 sm:px-0">
                    <h3 class="text-sm font-medium text-gray-900 sm:text-lg">Pending Hours Month</h3>

                    <p class="mt-2 text-sm text-gray-600">
                        <span
                            class="bg-green-500 text-white md:text-lg sm:text-xs font-bold md:mr-2 px-2.5 py-1 rounded-md sm:w-24 inline-block shadow-lg">
                                {{$PendingTodosHoursTotal}}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center relative p-3 w-full bg-gray-200 rounded shadow-md">
            <div class="w-full text-center">
                <div class="px-4 sm:px-0">
                    <h3 class="text-sm font-medium text-gray-900 sm:text-lg">Uploaded Hours Month</h3>

                    <p class="mt-2 text-sm text-gray-600">
                        <span
                            class="bg-green-500 text-white md:text-lg sm:text-xs font-bold md:mr-2 px-2.5 py-1 rounded-md sm:w-24 inline-block shadow-lg">
                                {{$PostedTodosHoursTotal}}
                        </span>
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
