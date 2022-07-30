<?php

namespace App\Http\Livewire;

use App\Models\Todo;
use App\Traits\InteractsWithModal;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Jetstream\InteractsWithBanner;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class PendingEntriesDataTable extends LivewireDatatable
{
    use InteractsWithModal;
    use InteractsWithBanner;

    public function builder(): Builder
    {
        return user()->pendingTodos()->getQuery();
    }

    public function columns(): array
    {
        return [
            Column::name('dated')->searchable()->label('Date')->sortable(),

            Column::callback(['hours'], static function ($hours) {
                return view('components.table-actions-badge', ['hours' => $hours, 'color' => 'green']);
            })->name('project.project_name')->label('Project')->searchable()->sortable(),

            Column::name('description')->searchable()->sortable(),
            Column::name('time_start')->sortable(),
            Column::name('time_end')->sortable(),

            NumberColumn::callback(['dated', 'time_start', 'time_end'], static function ($dated, $time_start, $time_end) {
                $hours = getBCHoursDiff($dated, $time_start, $time_end);

                return view('components.table-badge', ['value' => $hours, 'color' => 'green']);
            })->label('Total')->sortable(),

            Column::callback(['id'], static function ($id) {
                return view('components.table-actions-entry', ['id' => $id]);
            })->label('Action')->alignCenter(),
        ];
    }

    public function deleteItem($id): void
    {
        $todo = Todo::find($id);

        $todo->delete();

        $this->banner('Entry Deleted Successfully!');

        $this->emit('event-entries-updated');
    }
}
