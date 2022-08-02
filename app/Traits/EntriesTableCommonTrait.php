<?php

namespace App\Traits;

use App\Models\Todo;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;

trait EntriesTableCommonTrait
{
    // custom options
    public array $selectedItems = [];
    public int $selectedTotal = 0;

    // for select all functionality
    public string $checkedValues = '';

    public function columns(): array
    {
        return [

            Column::callback(['id'], static function ($id) {
                return view('components.table-actions-checkbox', ['id' => $id]);
            })->alignCenter()->excludeFromExport(),

            Column::name('dated')->searchable()->label('Date')->sortable(),

            Column::name('project.project_name')->label('Project')->searchable()->sortable(),

            Column::name('description')->searchable()->sortable(),
            Column::name('time_start')->sortable(),
            Column::name('time_end')->sortable(),

            NumberColumn::callback(['dated', 'time_start', 'time_end'], static function ($dated, $time_start, $time_end) {
                $hours = getBCHoursDiff($dated, $time_start, $time_end);

                return view('components.table-badge', ['value' => $hours, 'color' => 'green']);
            })->label('Total')->sortable(),

            Column::callback(['id', 'id', 'id'], function ($id) {
                return view('components.table-actions-entry', ['id' => $id, 'isPendingTable' => $this->isPendingTable]);
            })->label('Action')->alignCenter()->excludeFromExport(),
        ];
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
            $this->selectedItems = explode(',', $this->checkedValues);
            $this->setTotalHoursValue();
        }
    }

    public function uploadSelected(): void
    {
        $this->selectedItems = [];
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
