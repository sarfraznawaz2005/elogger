@props(['title', 'color' => 'gray'])

<div class="mx-auto p-0 border-0 mx-12 mb-4">

    @if (isset($title))
    <div class="bg-{{$color}}-300 text-gray-600 py-3 px-6 font-bold rounded-t-lg">
        {{$title}}
    </div>
    @endif

    <div class="p-6 bg-{{$color}}-200 rounded-b-lg text-gray-800 {{!isset($title) ? 'rounded-t-lg' : ''}}">
        {{$slot}}
    </div>
</div>
