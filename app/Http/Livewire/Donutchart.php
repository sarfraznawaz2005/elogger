<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Donutchart extends Component
{
    public function render(): Factory|View|Application
    {
        $monthHours = session('month_hours');
        $pendingHours = user()->pendingTodosHoursToday();
        $workDayCount = getWorkingDaysCount();

        return view('livewire.donutchart', compact('monthHours', 'pendingHours', 'workDayCount'));
    }
}
