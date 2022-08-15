@if (session()->has('main_user_id'))
    <div wire:ignore>
        <a href="{{route('exclude')}}"
           class="bg-green-700 ml-4 hover:bg-green-800 fixed bottom-4 text-sm font-bold right-16 z-50 shadow-md flex items-center text-white py-2 px-3 rounded"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>

            Revert
        </a>
    </div>
@endif
