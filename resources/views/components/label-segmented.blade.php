<div {{ $attributes->merge(['class' => "inline-flex items-center pl-2 text-sm font-medium text-center bg-$color-200 border border-$color-500 mr-2"])}}>
    {{ $title }}
    <span class="inline-flex justify-center items-center ml-1 p-2 text-xs font-bold bg-{{$color}}-400">
        {{ $value }}
    </span>
</div>
