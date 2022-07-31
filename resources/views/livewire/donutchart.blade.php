<div>
        <span
            wire:ignore
            class="inline-flex rounded-md"
            title="Current: {{round($monthHours + $pendingHours)}}/{{($workDayCount - user()->holidays_count) * user()->working_hours_count}}">
            <span
                class="donutChart hidden"
                data-peity='{ "fill": ["{{(round($monthHours + $pendingHours)) < (($workDayCount - user()->holidays_count) * user()->working_hours_count) ? '#E2BB29' : '#02C372'}}", "#eeeeee"], "innerRadius": 10, "radius": 15 }'>{{(round($monthHours) + $pendingHours)}}/{{$workDayCount * user()->working_hours_count}}
            </span>
        </span>

        <span
            wire:ignore
            class="inline-flex rounded-md ml-2"
            title="Projected: {{round($monthHours) + user()->working_hours_count}}/{{($workDayCount - user()->holidays_count) * user()->working_hours_count}}">
            <span
                class="donutChart hidden"
                data-peity='{ "fill": ["{{(round($monthHours) + user()->working_hours_count) < (($workDayCount - user()->holidays_count) * user()->working_hours_count) ? '#E2BB29' : '#02C372'}}", "#eeeeee"], "innerRadius": 10, "radius": 15 }'>{{(round($monthHours) + user()->working_hours_count)}}/{{$workDayCount * user()->working_hours_count}}
            </span>
    </span>
</div>

@push('js')
    <script src="/js/jquery.min.js"></script>
    <script src="/js/jquery.peity.min.js"></script>

    <script>
        Livewire.onLoad(() => {
            $(".donutChart").peity("donut");
        });

        Livewire.on('event-entries-updated', () => {
            // doesn't work - so added wire:ignore to elements above to ignore this
            $(".donutChart").peity("donut");
        });
    </script>
@endpush
