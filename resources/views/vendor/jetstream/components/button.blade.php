@props(['disabled' => false])

<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none disabled:opacity-25 transition']) }}
    {{ $disabled ?? false ? ' disabled' : '' }}
>
    {{ $slot }}
</button>
