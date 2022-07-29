<div class="bg-gray-200 overflow-hidden shadow-xl sm:rounded-lg p-4 my-4">

    <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-6 w-full">
        <div class="flex items-center relative p-4 w-full bg-white rounded-lg overflow-hidden shadow">
            <x-jet-section-title>
                <x-slot name="title">Pending Todos Hours Today</x-slot>
                <x-slot name="description">
                    <strong>
                        <span class="bg-green-100 text-green-800 text-2xl font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">
                            {{user()->pendingTodosHoursToday()}}
                        </span>
                    </strong>
                </x-slot>
            </x-jet-section-title>
        </div>

        <div class="flex items-center relative p-4 w-full bg-white rounded-lg overflow-hidden shadow">
            <x-jet-section-title>
                <x-slot name="title">Pending Todos Hours Total</x-slot>
                <x-slot name="description">
                    <strong>
                        <span class="bg-green-100 text-green-800 text-2xl font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">
                            {{user()->pendingTodosHours()}}
                        </span>
                    </strong>
                </x-slot>
            </x-jet-section-title>
        </div>

        <div class="flex items-center relative p-4 w-full bg-white rounded-lg overflow-hidden shadow">
            <x-jet-section-title>
                <x-slot name="title">Posted Todos Hours Total</x-slot>
                <x-slot name="description">
                    <strong>
                        <span class="bg-green-100 text-green-800 text-2xl font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-green-200 dark:text-green-900">
                            {{user()->postedTodosHours()}}
                        </span>
                    </strong>
                </x-slot>
            </x-jet-section-title>
        </div>
    </div>


</div>
