<?php

namespace App\Traits;

use App\Models\Todo;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;

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

            Column::name('dated')->searchable()->label('Date')->sortable(),

            Column::name('project.project_name')->label('Project')->searchable()->sortable(),

            Column::name('description')->searchable()->sortable(),

            Column::callback('time_start', static function ($time_start) {
                return date('h:i A', strtotime($time_start));
            })->label('Time Start')->sortable()->alignCenter(),

            Column::callback('time_end', static function ($time_end) {
                return date('h:i A', strtotime($time_end));
            })->label('Time End')->sortable()->alignCenter(),

            NumberColumn::callback(['dated', 'time_start', 'time_end'], static function ($dated, $time_start, $time_end) {
                $hours = getBCHoursDiff($dated, $time_start, $time_end);

                return <<<html
                    <span class="bg-green-100 text-green-800 text-md font-semibold px-2 py-1 rounded">
                        $hours
                    </span>
                html;

            })->label('Total')->sortable()->alignCenter(),

            Column::callback(['id', 'id'], function ($id) {
                return view('components.table-actions-entry', ['id' => $id, 'isPendingTable' => $this->isPendingTable]);
            })->label('Action')->alignCenter()->excludeFromExport(),
        ];

        // add select checkbox to pending table only
        if ($this->isPendingTable) {
            array_unshift($columns, Column::callback(['id'], static function ($id) {
                /** @noinspection ALL */
                return <<<html
                    <input type="checkbox" class="check-entry" wire:model="selectedItems" value="$id"/>
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

        /** @noinspection ALL */
        $todos = Todo::whereIn('id', $this->selectedItems)->get();

        foreach ($todos as $todo) {
            $diff = (float)getBCHoursDiff($todo->dated, $todo->time_start, $todo->time_end);

            $hours += $diff;
        }

        $this->selectedTotal = $hours;
    }
}
