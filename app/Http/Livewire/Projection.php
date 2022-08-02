<?php

namespace App\Http\Livewire;

use App\Traits\InteractsWithEvents;
use Livewire\Component;

class Projection extends Component
{
    use InteractsWithEvents;

    protected $listeners = ['event-entries-updated' => 'onEvent'];

    public function render(): string
    {
        $monthHours = session('month_hours') === 'none' ? '0.00' : session('month_hours');
        $pendingHours = user()->pendingTodosHoursToday();
        $workDayCount = getWorkingDaysCount();

        $cValue = number_format($monthHours + $pendingHours, 2) . '/' . number_format(($workDayCount - user()->holidays_count) * user()->working_hours_count, 2);
        $pValue = number_format(($monthHours) + user()->working_hours_count, 2) . '/' . number_format(($workDayCount - user()->holidays_count) * user()->working_hours_count, 2);

        $currentColor = (round($monthHours + $pendingHours)) < (($workDayCount - user()->holidays_count) * user()->working_hours_count) ? 'blue' : 'green';
        $projectedColor = (round($monthHours) + user()->working_hours_count) < (($workDayCount - user()->holidays_count) * user()->working_hours_count) ? 'blue' : 'green';

        return <<<blade
            <div>
                <x-label-segmented
                    color="$currentColor"
                    title="Current"
                    value="$cValue"/>

                <x-label-segmented
                    color="$projectedColor"
                    title="Projected"
                    value="$pValue"/>
            </div>
        blade;
    }
}
