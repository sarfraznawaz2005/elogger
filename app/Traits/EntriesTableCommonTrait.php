<?php

namespace App\Traits;

use JetBrains\PhpStorm\ArrayShape;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\NumberColumn;

trait EntriesTableCommonTrait
{
    // custom options
    public array $selectedItems = [];
    public int $selectedTotal = 0;

    public function columns(): array
    {
        return [

            Column::callback(['id', 'dated', 'time_start', 'time_end'], static function ($id, $dated, $time_start, $time_end) {
                $hours = getBCHoursDiff($dated, $time_start, $time_end);

                return view('components.table-actions-checkbox', ['id' => $id, 'hours' => $hours]);
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

    public function updated($propertyName): void
    {
        if ($propertyName === 'selectedItems') {

            $selectedHours = $this->getSelectedData()['selectedHours'];

            $this->selectedTotal = array_sum($selectedHours);
        }
    }

    public function uploadSelected(): void
    {
        $this->selectedItems = [];
    }

    #[ArrayShape(['selectedIds' => "array", 'selectedHours' => "array"])] private function getSelectedData(): array
    {
        $selectedIds = [];
        $selectedHours = [];

        foreach ($this->selectedItems as $item) {
            $array = explode('|', $item);

            $selectedIds[] = $array[0];
            $selectedHours[] = $array[1];
        }

        return [
            'selectedIds' => $selectedIds,
            'selectedHours' => $selectedHours,
        ];
    }
}
