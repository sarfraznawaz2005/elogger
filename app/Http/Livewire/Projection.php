<?php

namespace App\Http\Livewire;

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
    public bool $celebrated = false;

    public function render(): Factory|View|Application
    {
        $sad = <<< 'icon'
            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="#FFF700" viewBox="0 0 24 24" stroke="#A49F03" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        icon;

        $happy = <<< 'icon'
            <svg xmlns="http://www.w3.org/2000/svg" class="h-9 w-9" fill="#FFF700" viewBox="0 0 24 24" stroke="#A49F03" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        icon;

        $monthHoursUploaded = monthHoursUploaded();
        $pendingHoursMonth = user()->pendingTodosHoursMonth();
        $pendingHoursToday = user()->pendingTodosHoursToday();
        $workDayCount = getWorkingDaysCount();

        $cValue = round($monthHoursUploaded + $pendingHoursMonth) . '/' . round(($workDayCount - user()->holidays_count) * user()->working_hours_count);
        //$pValue = round(($monthHoursUploaded) + user()->working_hours_count) . '/' . round(($workDayCount - user()->holidays_count) * user()->working_hours_count);

        // projected when adding 8 eg working_hours_count
        $isHappy = !(($monthHoursUploaded + ($pendingHoursMonth - $pendingHoursToday) + user()->working_hours_count) < (($workDayCount - user()->holidays_count) * user()->working_hours_count));
        //dump($monthHoursUploaded + ($pendingHoursMonth - $pendingHoursToday) + user()->working_hours_count);

        if ($pendingHoursToday >= user()->working_hours_count) {
            $isHappy = !(($monthHoursUploaded + ($pendingHoursMonth - $pendingHoursToday) + $pendingHoursToday) < (($workDayCount - user()->holidays_count) * user()->working_hours_count));
        }

        if ($workDayCount - user()->holidays_count <= 0) {
            $isHappy = false;
        }

        $icon = $isHappy ? $happy : $sad;
        $title = $isHappy ? 'I am happy ;-)' : 'I am sad...';

        // celebrate when required hours are reached
        if ($isHappy && !$this->celebrated && !session()->has('celebrated')) {
            session()->put('celebrated', true);

            // also using this local variable because session takes place on page reload
            // so we handle both page reload and otherwise scenarios
            $this->celebrated = true;

            $this->dispatchBrowserEvent('celebrate');
        }

        return view(
            'livewire.projection',
            compact('cValue', 'icon', 'title', 'pendingHoursToday', 'pendingHoursMonth', 'monthHoursUploaded', 'workDayCount')
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
        if (!checkConnection()) {
            $this->danger('We are unable to communicate with Basecamp API, make sure you are connected to internet & your settings are correct.');

            session()->put('not_connected', true);

            return redirect()->to('/');
        }

        session()->forget('not_connected');

        refreshData();
        // session()->forget('app');

        $this->success('Data Refreshed Successfully!');

        return redirect()->to('/');
    }
}
