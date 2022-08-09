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
        'event-entries-updated' => 'setData',
        'refreshClicked' => 'refreshClicked',
        'refresh' => 'refresh',
    ];

    public string $cValue;
    public string $icon;
    public string $title;
    public string $pendingHoursToday;
    public string $pendingHoursMonth;
    public string $monthHoursUploaded;

    public bool $loading = false;
    public bool $loadingStats = true;
    public bool $celebrated = false;

    public function render(): Factory|View|Application
    {
        return view('livewire.projection');
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


    /** @noinspection ALL */
    public function loadProjection(): void
    {
        $this->setData();

        $this->loadingStats = false;
    }

    public function setData(): void
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

        $this->monthHoursUploaded = monthHoursUploaded() ?? '0.00';
        $this->pendingHoursMonth = user()->pendingTodosHoursMonth();
        $this->pendingHoursToday = user()->pendingTodosHoursToday();
        $workDayCount = getWorkingDaysCount();

        $this->cValue = round($this->monthHoursUploaded + $this->pendingHoursMonth) . '/' . round(($workDayCount - user()->holidays_count) * user()->working_hours_count);
        //$pValue = round(($this->monthHoursUploaded) + user()->working_hours_count) . '/' . round(($workDayCount - user()->holidays_count) * user()->working_hours_count);

        // projected when adding 8 eg working_hours_count
        $isHappy = !(($this->monthHoursUploaded + ($this->pendingHoursMonth - $this->pendingHoursToday) + user()->working_hours_count) < (($workDayCount - user()->holidays_count) * user()->working_hours_count));
        //dump($this->monthHoursUploaded + ($this->pendingHoursMonth - $this->pendingHoursToday) + user()->working_hours_count);

        if ($this->pendingHoursToday >= user()->working_hours_count) {
            $isHappy = !(($this->monthHoursUploaded + ($this->pendingHoursMonth - $this->pendingHoursToday) + $this->pendingHoursToday) < (($workDayCount - user()->holidays_count) * user()->working_hours_count));
        }

        if (getWorkingDaysCount() - user()->holidays_count <= 0) {
            $isHappy = false;
        }

        $this->icon = $isHappy ? $happy : $sad;
        $this->title = $isHappy ? 'I am happy ;-)' : 'I am sad...';

        // celebrate when required hours are reached
        if ($isHappy && !$this->celebrated && !session()->has('celebrated')) {
            session()->put('celebrated', true);

            // also using this local variable because session takes place on page reload
            // so we handle both page reload and otherwise scenarios
            $this->celebrated = true;

            $this->dispatchBrowserEvent('celebrate');
        }
    }
}
