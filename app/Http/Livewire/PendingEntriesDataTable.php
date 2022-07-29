<?php

namespace App\Http\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class PendingEntriesDataTable extends LivewireDatatable
{
    public function builder(): Builder
    {
        return user()->pendingTodos()->getQuery();
    }

    public function columns(): array
    {
        return [
            Column::name('dated')->searchable()->label('Date'),

            Column::callback(['hours'], static function ($hours) {
                return view('components.table-actions-badge', ['hours' => $hours, 'color' => 'green']);
            })->name('project.project_name')->label('Project'),

            Column::name('description')->searchable(),
            Column::name('time_start')->searchable(),
            Column::name('time_end')->searchable(),

            NumberColumn::callback(['dated', 'time_start', 'time_end'], static function ($dated, $time_start, $time_end) {
                $hours = getBCHoursDiff($dated, $time_start, $time_end);

                return view('components.table-actions-badge', ['hours' => $hours, 'color' => 'green']);
            })->label('Total'),
        ];
    }
}
