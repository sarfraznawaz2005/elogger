<div>
        <span
            class="inline-flex rounded-md"
            title="Current: {{round($monthHours + $pendingHours)}}/{{($workDayCount - user()->holidays_count) * user()->working_hours_count}}">
            <span
                class="donutChart"
                data-peity='{ "fill": ["{{(round($monthHours + $pendingHours)) < (($workDayCount - user()->holidays_count) * user()->working_hours_count) ? '#E2BB29' : '#02C372'}}", "#eeeeee"], "innerRadius": 10, "radius": 15 }'>{{(round($monthHours) + $pendingHours)}}/{{$workDayCount * user()->working_hours_count}}
            </span>
        </span>

        <span
            class="inline-flex rounded-md ml-2"
            title="Projected: {{round($monthHours) + user()->working_hours_count}}/{{($workDayCount - user()->holidays_count) * user()->working_hours_count}}">
            <span
                class="donutChart inline-flex rounded-md"
                data-peity='{ "fill": ["{{(round($monthHours) + user()->working_hours_count) < (($workDayCount - user()->holidays_count) * user()->working_hours_count) ? '#E2BB29' : '#02C372'}}", "#eeeeee"], "innerRadius": 10, "radius": 15 }'>{{(round($monthHours) + user()->working_hours_count)}}/{{$workDayCount * user()->working_hours_count}}
            </span>
    </span>
</div>
