<?php

namespace App\Http\Livewire;

use App\Services\Data;
use App\Traits\InteractsWithFlash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Projection extends Component
{
    use InteractsWithFlash;

    protected $listeners = [
        'event-entries-updated' => '$refresh',
        'refreshClicked' => 'refreshClicked',
        'refresh' => 'refresh',
    ];

    public bool $loading = false;

    public function render(): Factory|View|Application
    {
        $monthHours = session('month_hours') === 'none' ? '0.00' : session('month_hours');
        $pendingHours = user()->pendingTodosHoursToday();
        $workDayCount = getWorkingDaysCount();

        $cValue = round($monthHours + $pendingHours) . '/' . round(($workDayCount - user()->holidays_count) * user()->working_hours_count);
        $pValue = round(($monthHours) + user()->working_hours_count) . '/' . round(($workDayCount - user()->holidays_count) * user()->working_hours_count);

        $currentColor = (round($monthHours + $pendingHours)) < (($workDayCount - user()->holidays_count) * user()->working_hours_count) ? 'blue' : 'green';
        $projectedColor = (round($monthHours) + user()->working_hours_count) < (($workDayCount - user()->holidays_count) * user()->working_hours_count) ? 'blue' : 'green';

        return view(
            'livewire.projection',
            compact('cValue', 'pValue', 'currentColor', 'projectedColor')
        );
    }

    /** @noinspection ALL */
    public function refreshClicked(): void
    {
        $this->loading = true;

        $this->emitSelf('refresh');
    }

    /** @noinspection ALL */
    public function refresh()
    {
        Data::refreshData();

        $this->success('Data Refreshed Successfully!');

        return redirect()->to('/');
    }
}
