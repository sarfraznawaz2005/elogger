<?php

namespace App\Http\Livewire;

use App\Services\Data;
use App\Traits\InteractsWithEvents;
use App\Traits\InteractsWithFlash;
use Livewire\Component;

class Projection extends Component
{
    use InteractsWithEvents;
    use InteractsWithFlash;

    protected $listeners = [
        'event-entries-updated' => 'onEvent',
        'refresh' => 'refresh',
    ];

    public bool $loading = false;

    public function render(): string
    {
        $monthHours = session('month_hours') === 'none' ? '0.00' : session('month_hours');
        $pendingHours = user()->pendingTodosHoursToday();
        $workDayCount = getWorkingDaysCount();

        $cValue = round($monthHours + $pendingHours) . '/' . round(($workDayCount - user()->holidays_count) * user()->working_hours_count);
        $pValue = round(($monthHours) + user()->working_hours_count) . '/' . round(($workDayCount - user()->holidays_count) * user()->working_hours_count);

        $currentColor = (round($monthHours + $pendingHours)) < (($workDayCount - user()->holidays_count) * user()->working_hours_count) ? 'blue' : 'green';
        $projectedColor = (round($monthHours) + user()->working_hours_count) < (($workDayCount - user()->holidays_count) * user()->working_hours_count) ? 'blue' : 'green';

        return <<<blade
            <div class="inline-flex justify-center items-center">
                <x-label-segmented
                    color="$currentColor"
                    title="Current"
                    value="$cValue"/>

                <x-label-segmented
                    color="$projectedColor"
                    title="Projected"
                    value="$pValue"/>

                <x-jet-button wire:loading.attr="disabled" title="Refresh Data" wire:click="refreshClicked" class="bg-green-500 px-2.5 hover:bg-green-600 mr-3 border-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </x-jet-button>

                <x-status-modal wire:model="loading">
                    Please wait while we are refreshing data...
                </x-status-modal>
            </div>
        blade;
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
