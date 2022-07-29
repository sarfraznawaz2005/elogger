<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Stats extends Component
{
    public function render(): Factory|View|Application
    {
        $workDayCount = getWorkingDaysCount() - user()->holidays_count;
        $workDayCountMonth = getWorkingDaysCount(true) - user()->holidays_count;

        $workDays = "$workDayCount of $workDayCountMonth";
        $hoursLogged = session('month_hours');
        $hoursTotal = (getWorkingDaysCount(true) - user()->holidays_count) * user()->working_hours_count;

        return view('livewire.stats', compact('workDays', 'hoursLogged', 'hoursTotal'));
    }
}
