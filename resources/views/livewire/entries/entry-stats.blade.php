<div>
    <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-6 w-full">

        <div class="flex items-center relative p-4 w-full bg-white rounded-lg overflow-hidden shadow-md">
            <div class="w-full text-center">
                <div class="px-4 sm:px-0">
                    <h3 class="text-sm font-medium text-gray-900 sm:text-lg">Pending Hours Today</h3>

                    <p class="mt-3 text-sm text-gray-600">
                        <strong>
                        <span class="bg-green-100 text-green-800 text-2xl font-bold mr-2 px-2.5 py-1 rounded-lg">
                            {{$PendingTodosHoursToday}}
                        </span>
                        </strong>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center relative p-4 w-full bg-white rounded-lg overflow-hidden shadow-md">
            <div class="w-full text-center">
                <div class="px-4 sm:px-0">
                    <h3 class="text-sm font-medium text-gray-900 sm:text-lg">Pending Hours Total</h3>

                    <p class="mt-3 text-sm text-gray-600">
                        <strong>
                        <span class="bg-green-100 text-green-800 text-2xl font-bold mr-2 px-2.5 py-1 rounded-lg">
                            {{$PendingTodosHoursTotal}}
                        </span>
                        </strong>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center relative p-4 w-full bg-white rounded-lg overflow-hidden shadow-md">
            <div class="w-full text-center">
                <div class="px-4 sm:px-0">
                    <h3 class="text-sm font-medium text-gray-900 sm:text-lg">Uploaded Hours Total</h3>

                    <p class="mt-3 text-sm text-gray-600">
                        <strong>
                        <span class="bg-green-100 text-green-800 text-2xl font-bold mr-2 px-2.5 py-1 rounded-lg">
                            {{$PostedTodosHoursTotal}}
                        </span>
                        </strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
