<div class="bg-white overflow-hidden shadow-md sm:rounded-lg p-4 my-4">

    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-6 w-full">
        <div class="flex items-center relative p-4 w-full bg-gray-100 rounded-lg overflow-hidden shadow-md">
            <x-jet-section-title>
                <x-slot name="title">Work Day ({{date('d F Y')}})</x-slot>
                <x-slot name="description"><strong class="text-2xl">{{$workDays}}</strong></x-slot>
            </x-jet-section-title>
        </div>

        <div class="flex items-center relative p-4 w-full bg-gray-100 rounded-lg overflow-hidden shadow-md">
            <x-jet-section-title>
                <x-slot name="title">Hours Uploaded</x-slot>
                <x-slot name="description"><strong class="text-2xl">{{number_format($hoursUploaded, 2)}} of {{$hoursTotal}}</strong></x-slot>
            </x-jet-section-title>
        </div>
    </div>


</div>
