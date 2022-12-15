<div wire:key="table-table-actions-{{$id}}">

    {{--delete--}}
    <div class="inline" x-data="{tooltip: 'Delete'}">

        <button
            x-on:click="sendBrowserEvent('confirm', 'delete', '{{$id}}')"
            x-tooltip="tooltip"
            wire:loading.attr="disabled"
            class="cursor-pointer inline-table items-center px-2 py-1 text-white bg-red-600 hover:bg-red-800 rounded">
            <x-icons.delete/>
        </button>
    </div>

</div>
