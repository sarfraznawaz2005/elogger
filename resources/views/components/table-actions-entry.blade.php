<div wire:key="table-table-actions-{{$id}}">

    {{--duplicate--}}
    <div class="inline" x-data="{tooltip: 'Copy'}">
        <button wire:click="$emit('onDuplicateEntry', {{$id}})" wire:loading.attr="disabled" x-tooltip="tooltip" class="cursor-pointer inline-table items-center px-2 py-1 text-white bg-green-600 hover:bg-green-800 rounded">
            <x-icons.duplicate/>
        </button>
    </div>

    {{--edit--}}
    @if ($isPendingTable)
        <div class="inline" x-data="{tooltip: 'Edit'}">
            <button wire:click="$emit('onEditEntry', {{$id}})" wire:loading.attr="disabled" x-tooltip="tooltip" class="cursor-pointer inline-table items-center px-2 py-1 text-white bg-blue-600 hover:bg-blue-800 rounded">
                <x-icons.edit/>
            </button>
        </div>
    @endif

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

    {{--delete from basecamp --}}
    @if(!$isPendingTable)
        <div class="inline" x-data="{tooltip: 'Delete + Basecamp'}">
            <button
                x-on:click="sendBrowserEvent('confirm', 'onDeleteFromBasecamp', '{{$id}}', 'Sure to delete entry here plus basecamp ?')"
                x-tooltip="tooltip"
                wire:loading.attr="disabled"
                class="cursor-pointer inline-table items-center px-2 py-1 text-white bg-orange-600 hover:bg-orange-800 rounded">
                <x-icons.delete2/>
            </button>
        </div>
    @endif

</div>
