<?php

namespace App\Traits;

use App\Models\Project;
use App\Models\Todo;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\TimeColumn;

trait EntriesTableCommonTrait
{
    // custom options
    public array $selectedItems = [];
    public float $selectedTotal = 0;

    // for select all functionality
    public string $checkedValues = '';

    public function columns(): array
    {
        $columns = [

            Column::name('id')->hide()->label('ID')->defaultSort('desc'),

            DateColumn::name('dated')->label('Date'),

            // causing search issue as well. tbf
            Column::callback('project_id', static function ($project_id) {
                return getProjectNameForTodo($project_id);
            })->label('Project'),

            Column::callback('description', static function ($description) {
                $text = Str::limit($description, 60);

                return <<<html
                    <div class="inline" x-data="{tooltip: '$description'}">
                        <div x-tooltip="tooltip">$text</div>
                    </div>
                html;
            })->label('Description'),

            TimeColumn::name('time_start')->label('Time Start')->alignCenter(),
            TimeColumn::name('time_end')->label('Time End')->alignCenter(),

            NumberColumn::callback(['dated', 'time_start', 'time_end'], static function ($dated, $time_start, $time_end) {
                $hours = getBCHoursDiff($dated, $time_start, $time_end);

                return <<<html
                    <span class="bg-green-100 text-green-800 text-md font-semibold px-2 py-1 rounded">
                        $hours
                    </span>
                html;

            })->label('Total')->alignCenter(),

            Column::callback(['id', 'id'], function ($id) {
                return view('components.table-actions-entry', ['id' => $id, 'isPendingTable' => $this->isPendingTable]);
            })->label('Action')->alignCenter()->excludeFromExport(),
        ];

        // add select checkbox to pending table only
        if ($this->isPendingTable) {
            array_unshift($columns, Column::callback(['id'], static function ($id) {
                /** @noinspection ALL */
                return <<<html
                    <div wire:ignore wire:key="table-checkbox-$id">
                        <input type="checkbox" class="check-entry" wire:model="selectedItems" value="$id"/>
                    </div>
                html;
            })->alignCenter()->excludeFromExport());
        }

        return $columns;
    }

    public function booted(): void
    {
        $this->selectedItems = [];
        $this->selectedTotal = 0;
        $this->selectedvalues = '';
    }

    public function updated($propertyName): void
    {
        if ($propertyName === 'selectedItems') {
            $this->setTotalHoursValue();
        }

        if ($propertyName === 'checkedValues') {
            if ($this->checkedValues) {
                $this->selectedItems = explode(',', $this->checkedValues);
                $this->setTotalHoursValue();
            } else {
                $this->selectedItems = [];
                $this->selectedTotal = 0;
            }
        }
    }

    private function setTotalHoursValue(): void
    {
        $hours = 0;

        $todos = Todo::query()->whereIn('id', $this->selectedItems)->get();

        foreach ($todos as $todo) {
            $diff = (float)getBCHoursDiff($todo->dated, $todo->time_start, $todo->time_end);

            $hours += $diff;
        }

        $this->selectedTotal = $hours;
    }
}
