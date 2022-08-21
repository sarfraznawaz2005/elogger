<div {{ $attributes->merge(['class' => "inline-flex items-center pl-2 text-gray-900 text-sm font-medium text-center bg-$color-300 rounded mr-2"])}}>
    {{ $label }}
    <div class="inline-flex justify-center items-center ml-1 p-2 text-gray-900 text-xs font-bold bg-{{$color}}-400 rounded-r">
        {{ $value }}
    </div>
</div>
