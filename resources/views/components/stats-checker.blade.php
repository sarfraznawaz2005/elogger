@if(getWorkingDaysCount() - user()->holidays_count <= 0)
    <div class="pt-8">
        <div class="w-auto inline-block flex flex-row justify-center items-center">
            <div class="p-2 text-sm text-white break-words flex items-center bg-red-500">
                <div class="flex items-center justify-center">
                    <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="info-circle" class="w-4 h-4 mr-2 fill-current" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                        <path fill="currentColor" d="M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"></path>
                    </svg>
                    <p class="font-bold text-sm text-white break-words">
                        Seems like new month has started, you need to update "Total Holidays This Month" in your profile settings.
                        Otherwise, statistics might now show correctly.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endif
