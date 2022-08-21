@if(getWorkingDaysCount() - user()->holidays_count <= 0)
    <div class="w-auto inline-block flex justify-center items-center mb-4" wire:ignore>
        <div class="p-3 text-sm break-words flex items-center rounded-lg sky-box">
            <div class="flex items-center justify-center text-center">
                <x-icons.info/>

                <p class="font-bold text-sm text-gray-900 break-words">
                    Seems like new month has started, we have automatically reset your public holidays count setting.
                    Please refresh the page to see the changes.
                </p>
            </div>
        </div>
    </div>

    @php
        user()->update(['holidays_count' => 0])
    @endphp

@endif
