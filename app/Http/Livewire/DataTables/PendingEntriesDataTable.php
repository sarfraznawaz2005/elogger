<?php

namespace App\Http\Livewire\DataTables;

use App\Traits\EntriesTableCommonTrait;
use Illuminate\Database\Eloquent\Builder;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PendingEntriesDataTable extends LivewireDatatable
{
    use EntriesTableCommonTrait;

    /*
    protected $listeners = [
        'onDeleteSelected' => 'onDeleteSelected',
    ];
    */

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
    public bool $isPendingTable = true;

    public function builder(): Builder
    {
        return user()->pendingTodos()->getQuery();
    }

    public function onDeleteSelected(): void
    {
        $this->emit('onDeleteSelectedEntries', $this->selectedItems);
    }
}
