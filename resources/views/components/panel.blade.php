@props(['title', 'color' => 'gray'])

<div class="max-w-7xl mx-auto p-0 border-0 m-0 mb-4">
    <div class="bg-{{$color}}-300 text-gray-600 py-3 px-6 font-bold rounded-t-lg">
        {{$title}}
    </div>

    <div class="p-6 bg-{{$color}}-200 rounded-b-lg text-gray-800">
        {{$slot}}
    </div>
</div>
