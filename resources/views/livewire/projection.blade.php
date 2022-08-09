<div class="inline" wire:init="loadProjection" x-data="{show: @entangle('loadingStats')}">

    <svg x-show="show" class="inline w-7 h-7 text-gray-600 animate-spin" viewBox="0 0 100 101" fill="blue" xmlns="http://www.w3.org/2000/svg">
        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
    </svg>

    @if (!$loadingStats)
        <x-status-modal wire:model="loading">
            Please wait while we are refreshing data...
        </x-status-modal>

        <div class="inline-flex justify-center items-center">

            <div class="inline" x-data="{ open: false, working: false }" x-cloak wire:key="projection-{{ uniqid('', true) }}">

                <x-jet-button data-title="Refresh Data" x-on:click="open = true; working = false" class="bg-green-500 px-2.5 hover:bg-green-600 mr-2 border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </x-jet-button>

                @include('components.confirm', ['value' => $cValue, 'function' => 'refreshClicked', 'title' => 'Are you sure you want to refresh data ?'])
            </div>

            <x-label-segmented
                color="green"
                label="Progress"
                value="{{$cValue}}"/>

            <div class="cartoon" data-score="{{$monthHoursUploaded + ($pendingHoursMonth - $pendingHoursToday) + user()->working_hours_count}}" data-title="{{$title}}">{!! $icon !!}</div>

        </div>

        <style>
            .cartoon {
                transform: translateZ(1px);
                animation: cartoon-flip 4s cubic-bezier(0, 0.2, 0.8, 1) infinite;
                animation-iteration-count: 1;
            }

            @keyframes cartoon-flip {
                0%, 100% {
                    animation-timing-function: cubic-bezier(0.5, 0, 1, 0.5);
                }
                0% {
                    transform: rotateY(0deg);
                }
                50% {
                    transform: rotateY(1800deg);
                    animation-timing-function: cubic-bezier(0, 0.5, 0.5, 1);
                }
                100% {
                    transform: rotateY(3600deg);
                }
            }
        </style>
    @endif

</div>


