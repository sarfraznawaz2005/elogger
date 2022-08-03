<?php

namespace App\Http\Livewire\Dashboard;

use App\Services\Data;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class IndexDashboard extends Component
{
    public function render(): Factory|View|Application
    {
        $workDayCount = getWorkingDaysCount() - user()->holidays_count;
        $workDayCountMonth = getWorkingDaysCount(true) - user()->holidays_count;

        $workDays = "$workDayCount of $workDayCountMonth";
        $hoursUploaded = session('month_hours') === 'none' ? '0.00' : session('month_hours');
        $hoursTotal = (getWorkingDaysCount(true) - user()->holidays_count) * user()->working_hours_count;

        $allUsersHours = [];
        $projects = collect(Data::getUserProjectlyHours())->sortByDesc('hours');

        if (session('all_users_hours') && user()->isAdmin()) {
            $allUsersHours = session('all_users_hours');
        }

        return view(
            'livewire.dashboard.index',
            compact('workDays', 'hoursUploaded', 'hoursTotal', 'projects', 'allUsersHours')
        );
    }
}
