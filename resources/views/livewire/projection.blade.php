<div class="inline">
    @if (hasBasecampSetup())

        <x-status-modal wire:model="loading">
            Please wait while we are refreshing data...
        </x-status-modal>

        <div class="inline-flex justify-center items-center">

            <div class="inline" x-data="{tooltip: 'Refresh Data'}" x-cloak>
                <x-jet-button
                    x-on:click="sendBrowserEvent('confirm', 'refreshClicked', null, 'Are you sure you want to refresh data ?')"
                    wire:loading.attr="disabled"
                    x-tooltip="tooltip"
                    class="bg-green-500 px-2.5 hover:bg-green-600 mr-2 border-0">
                    <x-icons.refresh class="mr-0"/>
                </x-jet-button>
            </div>

            <x-label-segmented
                color="green"
                label="Progress"
                class="hidden sm:flex"
                value="{{$cValue}}"/>

            <div data-score="{{$calc}} = {{$projected}}" class="inline" x-data="{tooltip: '{{$title}}'}">
                <div class="cartoon" x-tooltip="tooltip">{!! $icon !!}</div>
            </div>

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
