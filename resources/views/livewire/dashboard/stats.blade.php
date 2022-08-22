<div class="mb-6" wire:ignore>

    <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-6 w-full">

        <div class="flex items-center relative p-3 w-full gray-box rounded-md">
            <div class="w-full text-center">
                <div class="px-4 sm:px-0">
                    <h3 class="text-sm font-medium text-gray-900 sm:text-lg">Work Day ({{date('d M Y')}})</h3>

                    <p class="mt-2 text-sm text-gray-600">
                        <strong class="md:text-2xl sm:text-xs">{{$workDays}}</strong>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center relative p-3 w-full gray-box rounded-md">
            <div class="w-full text-center">
                <div class="px-4 sm:px-0">
                    <h3 class="text-sm font-medium text-gray-900 sm:text-lg">Hours Uploaded</h3>

                    <p class="mt-2 text-sm text-gray-600">
                        <strong class="md:text-2xl sm:text-xs">{{round($hoursUploaded)}} of {{$hoursTotal}}</strong>
                    </p>
                </div>
            </div>
        </div>

        <div class="flex items-center relative p-3 w-full gray-box rounded-md">
            <div class="w-full text-center">
                <div class="px-4 sm:px-0">
                    <h3 class="text-sm font-medium text-gray-900 sm:text-lg">Hours Projected</h3>

                    <p class="mt-2 text-sm text-gray-600">
                        <strong class="md:text-2xl sm:text-xs">{{$hoursProjected}} of {{$hoursTotal}}</strong>
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
