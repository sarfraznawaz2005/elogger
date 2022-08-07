<?php

namespace App\Http\Livewire\Dashboard;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class IndexDashboard extends Component
{
    public function render(): Factory|View|Application
    {
        //session()->forget('celebrated');

        //$workDayCount = getWorkingDaysCount() - user()->holidays_count;
        $workDayCount = getWorkingDaysCount();
        $workDayCountMonth = workDayCountMonth();

        $workDays = "$workDayCount of $workDayCountMonth";
        $hoursUploaded = monthHoursUploaded();
        $hoursProjected = monthProjectedHours($workDayCount, $workDayCountMonth);
        $hoursTotal = workMonthRequiredHours($workDayCountMonth);

        $allUsersHours = [];
        $projects = collect(getUserProjectlyHours())->sortByDesc('hours');

        if (session('all_users_hours') && user()->isAdmin()) {
            $allUsersHours = session('all_users_hours');
        }

        return view(
            'livewire.dashboard.index',
            compact('workDays', 'hoursUploaded', 'hoursProjected', 'hoursTotal', 'projects', 'allUsersHours')
        );
    }
}
