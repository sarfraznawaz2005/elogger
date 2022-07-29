<?php

namespace App\Http\Livewire;

use App\Traits\InteractsWithEvents;
use Illuminate\Database\Eloquent\Builder;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class PendingEntriesDataTable extends LivewireDatatable
{
    use InteractsWithEvents;

    protected $listeners = ['event-entry-saved' => 'onEvent'];

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

                return view('components.table-badge', ['value' => $hours, 'color' => 'green']);
            })->label('Total'),

            Column::callback(['id', 'project.project_name'], static function ($id, $name) {
                return view('components.table-actions-entry', ['id' => $id, 'name' => $name]);
            })->label('Action')->alignCenter(),
        ];
    }
}
