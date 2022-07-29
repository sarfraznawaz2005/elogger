<?php

namespace App\Http\Livewire;

use App\Models\BasecampProject;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class ProjectsDataTable extends LivewireDatatable
{
    public $model = BasecampProject::class;

    public function columns(): array
    {
        return [
            Column::name('date')->searchable(),
            Column::name('description')->searchable(),

            NumberColumn::callback(['hours'], static function ($hours) {
                return view('components.table-actions-badge', ['hours' => $hours, 'color' => 'green']);
            })->label('Hours'),
        ];
    }
}
