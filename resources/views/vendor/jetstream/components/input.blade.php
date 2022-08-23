@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'focus:border focus:ring-0 border-gray-300 focus:border-blue-600 focus:ring focus:ring-blue-200 rounded-md shadow-sm']) !!}>
