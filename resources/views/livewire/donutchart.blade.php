<div>

    @php
        $currentColor = (round($monthHours + $pendingHours)) < (($workDayCount - user()->holidays_count) * user()->working_hours_count) ? 'blue' : 'green';
        $projectedColor = (round($monthHours) + user()->working_hours_count) < (($workDayCount - user()->holidays_count) * user()->working_hours_count) ? 'blue' : 'green';
    @endphp

    <x-label-segmented color="{{$currentColor}}">
        <x-slot name="title">
            Current
        </x-slot>
        <x-slot name="value">
            {{round($monthHours + $pendingHours)}}/{{($workDayCount - user()->holidays_count) * user()->working_hours_count}}
        </x-slot>
    </x-label-segmented>

    <x-label-segmented color="{{$projectedColor}}">
        <x-slot name="title">
            Projected
        </x-slot>
        <x-slot name="value">
            {{round($monthHours) + user()->working_hours_count}}/{{($workDayCount - user()->holidays_count) * user()->working_hours_count}}
        </x-slot>
    </x-label-segmented>

</div>
