<div class="bg-gray-200 overflow-hidden shadow-xl sm:rounded-lg p-3 my-4">

    <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-6 w-full">
        <div class="flex items-center relative p-2 w-full bg-white rounded-lg overflow-hidden shadow">
            <div class="w-full text-center">
                <div class="px-4 sm:px-0">
                    <h3 class="text-sm font-medium text-gray-900 sm:text-lg">Pending Todos Hours Today</h3>

                    <p class="mt-1 text-sm text-gray-600">
                        <strong>
                        <span class="bg-green-100 text-green-800 text-2xl font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">
                            {{$PendingTodosHoursToday}}
                        </span>
                        </strong>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center relative p-2 w-full bg-white rounded-lg overflow-hidden shadow">
            <div class="w-full text-center">
                <div class="px-4 sm:px-0">
                    <h3 class="text-sm font-medium text-gray-900 sm:text-lg">Pending Todos Hours Total</h3>

                    <p class="mt-1 text-sm text-gray-600">
                        <strong>
                        <span class="bg-green-100 text-green-800 text-2xl font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">
                            {{$PendingTodosHoursTotal}}
                        </span>
                        </strong>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center relative p-2 w-full bg-white rounded-lg overflow-hidden shadow">
            <div class="w-full text-center">
                <div class="px-4 sm:px-0">
                    <h3 class="text-sm font-medium text-gray-900 sm:text-lg">Posted Todos Hours Total</h3>

                    <p class="mt-1 text-sm text-gray-600">
                        <strong>
                        <span class="bg-green-100 text-green-800 text-2xl font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">
                            {{$PostedTodosHoursTotal}}
                        </span>
                        </strong>
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>
