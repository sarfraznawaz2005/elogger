<?php

namespace App\Http\Livewire\DataTables;

use Illuminate\Database\Eloquent\Builder;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class PendingEntriesDataTable extends LivewireDatatable
{
    // table specific options
    public $persistSearch = false;
    public $persistComplexQuery = false;
    public $persistHiddenColumns = false;
    public $persistSort = false;
    public $persistPerPage = false;
    public $persistFilters = false;

    public $afterTableSlot = 'components.table-actions-post-delete-buttons';

    public $hideable = [
        'id',
        'select',
        'buttons',
        'inline',
    ];

    // custom options

    public array $selectedItems = [];

    public function builder(): Builder
    {
        return user()->pendingTodos()->getQuery();
    }

    public function columns(): array
    {
        return [

            Column::callback(['id', 'id'], static function ($id) {
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

            Column::callback(['id', 'id', 'id'], static function ($id) {
                return view('components.table-actions-entry', ['id' => $id]);
            })->label('Action')->alignCenter()->excludeFromExport(),
        ];
    }


}
