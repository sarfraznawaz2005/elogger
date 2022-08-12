<?php

namespace App\Http\Livewire\Entries;

use App\Traits\InteractsWithModal;
use App\Traits\InteractsWithToast;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;

/** @noinspection ALL */

class ViewMyHoursLog extends Component
{
    use InteractsWithModal;
    use InteractsWithToast;

    protected $listeners = [
        'onLoad' => 'onLoad',
        'viewMyHoursLog' => 'viewMyHoursLog'
    ];

    public Collection $items;

    public array $workingDatesTillToday = [];

    public bool $loading = false;

    public function render(): Factory|View|Application
    {
        return view('livewire.entries.view-my-hours-log');
    }

    public function onLoad(): void
    {
        $this->loading = true;

        $this->workingDatesTillToday = getDatesTillToday();

        if ((!session('uploaded_hours_today')) > 0) {
            array_shift($this->workingDatesTillToday);
        }

        $this->emitSelf('viewMyHoursLog');
    }

    /** @noinspection ALL */
    public function viewMyHoursLog(): void
    {
        $items = [];

        $data = getWorkedHoursData();

        if (!isset($data['time-entry'])) {
            $this->loading = false;
            $this->danger('No hours uploaded yet.');
            return;
        }

        // for when single record is returned
        $entry = (array)$data['time-entry'];

        if (isset($entry['hours'])) {
            $items[] = [
                'dated' => strtotime($entry['date']),
                'date' => date('d F Y', strtotime($entry['date'])),
                'hours' => $entry['hours']
            ];
        } else {
            foreach ($data['time-entry'] as $timeEntryXML) {
                $entry = (array)$timeEntryXML;

                if (isset($entry['hours'])) {
                    $items[] = [
                        'dated' => strtotime($entry['date']),
                        'date' => date('d F Y', strtotime($entry['date'])),
                        'hours' => $entry['hours']
                    ];
                }
            }
        }

        $this->items = collect($items)->sortByDesc('dated');

        $this->items = $this->items->groupBy('date', false)->map(static function ($items) {
            return $items->sum('hours');
        });

        $this->loading = false;

        $this->openModal();
    }
}
