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
        'onLoad',
        'viewMyHoursLog',
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

        $this->emitSelf('viewMyHoursLog');
    }

    /** @noinspection ALL */
    public function viewMyHoursLog(): void
    {
        $items = $this->parseItems(getWorkedHoursData());

        if ($monthPendingDates = $this->pendingHoursMonth()) {
            $items = $items + $monthPendingDates;
        }

        if (!$items) {
            $this->loading = false;
            $this->danger('No hours yet.');
            return;
        }

        $this->items = collect($items)->sortByDesc('dated');

        $this->items = $this->items->groupBy('date', false)->map(static function ($items) {
            return $items->sum('hours');
        });

        // also add pending hours
        $PendingTodosHoursTotal = number_format(user()->pendingTodosHoursMonth(), 2);

        $this->loading = false;

        $this->openModal();
    }

    private function parseItems($data): array
    {
        $items = [];

        if (!isset($data['time-entry'])) {
            return [];
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

        return $items;
    }

    private function pendingHoursMonth(): array
    {
        $todos = user()->pendingTodos()
            ->select('dated', 'time_start', 'time_end')
            ->whereMonth('dated', date('m'))
            ->get()
            ->groupBy(function ($item) {
                return date('d F Y', strtotime($item->dated));
            })
            ->toArray();

        return collect($todos)->map(static function ($items) {
            $hours = 0;

            foreach ($items as $item) {
                $diff = (float)getBCHoursDiff($item['dated'], $item['time_start'], $item['time_end']);

                $hours += $diff;
            }

            return [
                'dated' => strtotime($items[0]['dated']),
                'date' => date('d F Y', strtotime($items[0]['dated'])),
                'hours' => $hours
            ];
        })->all();
    }
}
