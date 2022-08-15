<div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-4 mb-6" wire:ignore>

    <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-6 w-full">

        <div class="flex items-center relative p-2 w-full bg-gray-100 rounded-lg overflow-hidden shadow-md">
            <div class="w-full text-center">
                <div class="px-4 sm:px-0">
                    <h3 class="text-sm font-medium text-gray-900 sm:text-lg">Work Day ({{date('d F Y')}})</h3>

                    <p class="mt-1 text-sm text-gray-600">
                        <strong class="md:text-2xl sm:text-xs">{{$workDays}}</strong>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center relative p-2 w-full bg-gray-100 rounded-lg overflow-hidden shadow-md">
            <div class="w-full text-center">
                <div class="px-4 sm:px-0">
                    <h3 class="text-sm font-medium text-gray-900 sm:text-lg">Hours Uploaded</h3>

                    <p class="mt-1 text-sm text-gray-600">
                        <strong class="md:text-2xl sm:text-xs">{{number_format($hoursUploaded, 2)}} of {{$hoursTotal}}</strong>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center relative p-2 w-full bg-gray-100 rounded-lg overflow-hidden shadow-md">
            <div class="w-full text-center">
                <div class="px-4 sm:px-0">
                    <h3 class="text-sm font-medium text-gray-900 sm:text-lg">Hours Projected</h3>

                    <p class="mt-1 text-sm text-gray-600">
                        <strong class="md:text-2xl sm:text-xs">{{$hoursProjected}} of {{$hoursTotal}}</strong>
                    </p>
                </div>
            </div>
        </div>

    </div>


</div>
