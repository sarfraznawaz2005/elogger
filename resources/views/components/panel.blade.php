@props(['title', 'color' => 'gray'])

<div class="mx-auto p-0 border-0 mx-12 mb-4">

    @if (isset($title))
    <div class="bg-{{$color}}-300 text-gray-600 {{isset($headerRight) ? 'py-2' : 'py-3'}} px-6 font-bold rounded-t-lg">
        <div class="flex justify-between items-center">
            <div>{{$title}}</div>
            @if (isset($headerRight))
                <div>{{$headerRight}}</div>
            @endif
        </div>
    </div>
    @endif

	@php
		$roundedValue = !isset($title) ? 'rounded-t-lg' : '';
	@endphp

    <div {{$attributes->class("p-5 bg-$color-50 rounded-b-lg text-gray-800 border border-gray-300 border-t-0 $roundedValue")}}>
        {{$slot}}
    </div>
</div>
