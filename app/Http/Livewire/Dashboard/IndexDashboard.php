<?php

namespace App\Http\Livewire\Dashboard;

use Colors\RandomColor;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class IndexDashboard extends Component
{
    public bool $loading = true;

    public function render(): Factory|View|Application
    {
        //session()->forget('celebrated');

        $workDayCount = getWorkingDaysCount() - user()->holidays_count;
        $workDayCountMonth = workDayCountMonth();

        $workDays = "$workDayCount of $workDayCountMonth";
        $hoursUploaded = monthHoursUploaded();
        $hoursProjected = monthProjectedHours($workDayCountMonth);
        $hoursTotal = workMonthRequiredHours($workDayCountMonth);

        $allUsersHours = [];
        $projects = collect([]);

        if (session('month_hours')) {
            $projects = collect(getUserProjectlyHours())
                ->sortByDesc('hours')
                ->pluck('project_name', 'hours')
                ->toArray();
        }

        if (session('all_users_hours') && user()->isAdmin()) {
            $allUsersHours = session('all_users_hours');
        }

        // colors
        $pieColors = RandomColor::many(count($projects), ['luminosity' => 'dark', 'hue' => 'random', 'format' => 'rgbCss']);
        $barColors = RandomColor::many(count($allUsersHours), ['luminosity' => 'dark', 'hue' => 'random', 'format' => 'rgbCss']);

        return view(
            'livewire.dashboard.index',
            compact('workDays',
                'hoursUploaded',
                'hoursProjected',
                'hoursTotal',
                'projects',
                'allUsersHours',
                'pieColors',
                'barColors'
            )
        );
    }

    public function loadCharts(): void
    {
        $this->loading = false;
    }
}
