<div>

    <x-status-modal wire:model="loading">
        Loading...
    </x-status-modal>

    <button type="button"
            class="inline-flex items-center px-4 py-2 bg-blue-700 hover:bg-blue-800 active:bg-blue-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none disabled:opacity-25 transition"
            wire:click="$emitSelf('onLoad')">

        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>

        {{ __('My Hours Log') }}
    </button>

    <x-jet-dialog-modal wire:model="isModalOpen" maxWidth="sm">

        <x-slot name="title">
            My Hours Log This Month
        </x-slot>

        <x-slot name="content">

            <div class="overflow-y-hidden">
                <x-bc-error/>

                @if(isset($items) && $count = $items->count())

                    <div class="flex justify-between py-2 px-4">
                        <div>Total</div>
                        <div class="rounded p-1 font-bold w-20 text-lg text-center gray-box">{{number_format($items->sum(), 2)}}</div>
                    </div>

                    @foreach($workingDatesTillToday as $date)
                        @if($items->has($date))

                            @php
                                $hours = $items->get($date);
                                $bgColor = $hours < 8 ? ($hours < 1 ? 'red-light-box' : 'yellow-light-box') : 'green-light-box';
                                $bgColor = isWeekend($date) ? 'blue-light-box' : $bgColor;
                            @endphp

                            <div class="flex items-center justify-between border-t border-gray-300 py-2 px-4">
                                <div>{{$date}}</div>
                                <div class="rounded p-1 font-bold w-20 text-center {{$bgColor}}">
                                    {{number_format($hours, 2)}}
                                </div>
                            </div>
                        @else
                            @if(!isWeekend($date))
                                <div class="flex items-center justify-between border-t border-gray-300 py-2 px-4">
                                    <div>{{$date}}</div>
                                    <div class="rounded p-1 font-bold w-20 text-center red-light-box">
                                        0.00
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach
                @endif
            </div>

        </x-slot>

        <x-slot name="footer">
            <x-jet-button x-on:click="show = false" wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Close')  }}
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
