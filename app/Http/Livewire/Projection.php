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
        $sad = <<< icon
            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="#FFF700" viewBox="0 0 24 24" stroke="#A49F03" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        icon;

        $happy = <<< icon
            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="#FFF700" viewBox="0 0 24 24" stroke="#A49F03" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        icon;

        $monthHours = session('month_hours') === 'none' ? '0.00' : session('month_hours');
        $pendingHours = user()->pendingTodosHoursToday();
        $workDayCount = getWorkingDaysCount();

        $cValue = round($monthHours + $pendingHours) . '/' . round(($workDayCount - user()->holidays_count) * user()->working_hours_count);
        $pValue = round(($monthHours) + user()->working_hours_count) . '/' . round(($workDayCount - user()->holidays_count) * user()->working_hours_count);

        $isHappy = !((round($monthHours) + user()->working_hours_count) < (($workDayCount - user()->holidays_count) * user()->working_hours_count));

        $icon = $isHappy ? $happy : $sad;
        $title = $isHappy ? 'I am happy ;-)' : 'Make me happy please!';

        return view(
            'livewire.projection',
            compact('cValue', 'pValue', 'icon', 'title')
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
        // session()->forget('app');

        $this->success('Data Refreshed Successfully!');

        return redirect()->to('/');
    }
}
