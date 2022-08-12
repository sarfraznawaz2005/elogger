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
        $defaultFace = '🙄';

        $sadFaces = ['😟', '🤒', '🤕', '🤢', '🥺', '😥', '😭', '😡'];
        $happyFaces = ['😀', '😃', '🙂', '🙃', '😊', '🥰', '🤗', '🤠', '😎'];

        $sadFace = $sadFaces[array_rand($sadFaces)];
        $happyFace = $happyFaces[array_rand($happyFaces)];

        // default
        if (!session('month_hours')) {
            $sadFace = $defaultFace;
        }

        $sad = <<< icon
            <div class="flex items-center">
                <span style="font-size: 27px; padding-top: 2px;">$sadFace</span>
            </div>
        icon;

        $happy = <<< icon
            <div class="flex items-center">
                <span style="font-size: 27px; padding-top: 2px;">$happyFace</span>
            </div>
        icon;

        $monthHoursUploaded = monthHoursUploaded();
        $pendingHoursMonth = user()->pendingTodosHoursMonth();
        $pendingHoursToday = user()->pendingTodosHoursToday();
        $workDayCount = getWorkingDaysCount();
        $holidayCount = user()->holidays_count;
        $workingHoursCount = user()->working_hours_count;

        $totalRequiredTillToday = round(($workDayCount - $holidayCount) * $workingHoursCount);

        $cValue = round($monthHoursUploaded + $pendingHoursMonth) . '/' . $totalRequiredTillToday;

        // projected when adding 8 eg working_hours_count
        $add = $pendingHoursToday > $workingHoursCount ? $pendingHoursToday : $workingHoursCount;
        $projected = round($monthHoursUploaded + ($pendingHoursMonth - $pendingHoursToday) + $add);

        // if user has already uploaded hours for today, substract them from projection
        $projected -= session('uploaded_hours_today') > 0 ? $workingHoursCount : 0;

        $isHappy = !($projected < $totalRequiredTillToday);

        if ($workDayCount - $holidayCount <= 0) {
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

            // strange but needed this otherwise session was not being set
            // most likely some livewire stuff.
            sleep(1);

            $this->dispatchBrowserEvent('celebrate');
        }

        return view(
            'livewire.projection',
            compact('cValue', 'icon', 'title', 'projected')
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

            // strange but needed this otherwise session was not being set
            // most likely livewire was refreshing page quickly again.
            sleep(1);

            return redirect()->to('/');
        }

        session()->forget('not_connected');

        refreshData();
        // session()->forget('app');

        $this->success('Data Refreshed Successfully!');

        return redirect()->to('/');
    }
}
