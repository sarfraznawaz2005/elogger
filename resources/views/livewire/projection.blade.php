<div class="inline-flex justify-center items-center">

    <x-status-modal wire:model="loading">
        Please wait while we are refreshing data...
    </x-status-modal>

    <x-label-segmented
        color="{{$currentColor}}"
        title="Current"
        value="{{$cValue}}"/>

    <x-label-segmented
        color="{{$projectedColor}}"
        title="Projected"
        value="{{$pValue}}"/>

    <div class="inline" x-data="{ open: false, working: false }" x-cloak wire:key="projection-{{ uniqid('', true) }}">

        <x-jet-button wire:loading.attr="disabled" title="Refresh Data" x-on:click="open = true; working = false" class="bg-green-500 px-2.5 hover:bg-green-600 mr-3 border-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
        </x-jet-button>

        @include('components.confirm', ['value' => $pValue, 'function' => 'refreshClicked', 'title' => 'Are you sure you want to refresh data ?'])
    </div>

</div>
