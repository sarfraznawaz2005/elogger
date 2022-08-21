@if(session()->has('not_connected'))
    <div class="w-auto inline-block flex justify-center items-center mb-4" wire:ignore>
        <div class="p-3 text-sm text-white break-words flex items-center justify-center text-center rounded-lg red-box">
            <div>
                <x-icons.info/> We are unable to communicate with Basecamp!
            </div>
        </div>
    </div>
@endif
