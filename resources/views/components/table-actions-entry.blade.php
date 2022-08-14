<div wire:key="table-table-actions-{{$id}}">

    {{--duplicate--}}
    <div class="inline" x-data="{tooltip: 'Copy'}">
        <button wire:click="$emit('onDuplicateEntry', {{$id}})" wire:loading.attr="disabled" x-tooltip="tooltip" class="cursor-pointer inline-table items-center px-2 py-1 text-white bg-green-600 hover:bg-green-800 rounded">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
        </button>
    </div>

    {{--edit--}}
    @if ($isPendingTable)
        <div class="inline" x-data="{tooltip: 'Edit'}">
            <button wire:click="$emit('onEditEntry', {{$id}})" wire:loading.attr="disabled" x-tooltip="tooltip" class="cursor-pointer inline-table items-center px-2 py-1 text-white bg-blue-600 hover:bg-blue-800 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
            </button>
        </div>
    @endif

    {{--delete--}}
    <div class="inline" x-data="{ open: false, working: false, tooltip: 'Delete' }" x-cloak>

        <button x-on:click="open = true" x-tooltip="tooltip" class="cursor-pointer inline-table items-center px-2 py-1 text-white bg-red-600 hover:bg-red-800 rounded">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
        </button>

        @include('components.confirm', ['value' => $id, 'function' => 'delete'])
    </div>

    {{--delete from basecamp --}}
    @if(!$isPendingTable)
        <div class="inline" x-data="{ open: false, working: false, tooltip: 'Delete + Basecamp' }" x-cloak>

            <button x-on:click="open = true" x-tooltip="tooltip" class="cursor-pointer inline-table items-center px-2 py-1 text-white bg-red-600 hover:bg-red-800 rounded">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
            </button>

            @include('components.confirm', ['value' => $id, 'function' => 'onDeleteFromBasecamp', 'title' => 'Sure to delete entry here plus basecamp ?'])
        </div>
    @endif

</div>
