<div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-6 w-full mb-1">

    <div class="flex items-center relative p-3 pb-4 gray-box rounded-md">
        <div class="w-full text-center">
            <div class="px-4 sm:px-0">
                <h3 class="text-sm font-medium text-gray-900 sm:text-lg">Pending Hours Today</h3>

                <p class="mt-2 text-sm">
                        <span
                            class="green-box text-white md:text-lg sm:text-xs font-bold px-2.5 py-1 rounded-md sm:w-24 inline-block">
                                {{number_format(user()->pendingTodosHoursToday(), 2)}}
                        </span>
                </p>
            </div>
        </div>
    </div>

    <div class="flex items-center relative p-3 pb-4 gray-box rounded-md">
        <div class="w-full text-center">
            <div class="px-4 sm:px-0">
                <h3 class="text-sm font-medium text-gray-900 sm:text-lg">Pending Hours Month</h3>

                <p class="mt-2 text-sm">
                        <span
                            class="green-box text-white md:text-lg sm:text-xs font-bold px-2.5 py-1 rounded-md sm:w-24 inline-block">
                                {{number_format(user()->pendingTodosHoursMonth(), 2)}}
                        </span>
                </p>
            </div>
        </div>
    </div>

    <div class="flex items-center relative p-3 pb-4 gray-box rounded-md">
        <div class="w-full text-center">
            <div class="px-4 sm:px-0">
                <h3 class="text-sm font-medium text-gray-900 sm:text-lg">Uploaded Hours Month</h3>

                <p class="mt-2 text-sm">
                        <span
                            class="green-box text-white md:text-lg sm:text-xs font-bold px-2.5 py-1 rounded-md sm:w-24 inline-block">
                                {{number_format(monthHoursUploaded(), 2)}}
                        </span>
                </p>
            </div>
        </div>
    </div>

</div>
