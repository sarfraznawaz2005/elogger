{{--duplicate--}}
<button wire:click="$emit('onDuplicateEntry', {{$id}})" wire:loading.attr="disabled" title="Duplicate" class="cursor-pointer inline-table items-center px-2 py-1 text-white bg-green-600 hover:bg-green-800 rounded">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
    </svg>
</button>

{{--view--}}
<button wire:click="$emit('onViewEntry', {{$id}})" wire:loading.attr="disabled" title="View" class="cursor-pointer inline-table items-center px-2 py-1 text-white bg-yellow-600 hover:bg-yellow-800 rounded">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
</button>

{{--edit--}}
@if ($isPendingTable)
    <button wire:click="$emit('onEditEntry', {{$id}})" wire:loading.attr="disabled" title="Edit" class="cursor-pointer inline-table items-center px-2 py-1 text-white bg-blue-600 hover:bg-blue-800 rounded">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
        </svg>
    </button>
@endif

{{--delete--}}
<div class="inline" x-data="{ open: false, working: false, showLoading: false }" x-cloak wire:key="delete-entry-{{ $id }}" x-init="document.addEventListener('hide-waiting-message', () => showLoading = false);">

    <button x-on:click="open = true" title="Delete" class="cursor-pointer inline-table items-center px-2 py-1 text-white bg-red-600 hover:bg-red-800 rounded">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
    </button>

    @include('components.delete-confirm', ['value' => $id, 'function' => 'onDeleteEntry'])
</div>
