<?php

namespace App\Http\Livewire\DataTables;

use App\Traits\EntriesTableCommonTrait;
use Illuminate\Database\Eloquent\Builder;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PendingEntriesDataTable extends LivewireDatatable
{
    use EntriesTableCommonTrait;

    // table specific options
    public $persistSearch = false;
    public $persistComplexQuery = false;
    public $persistHiddenColumns = false;
    public $persistSort = false;
    public $persistPerPage = false;
    public $persistFilters = false;

    public bool $hidePageSize = false;

    public $beforeTableSlot = 'components.table-actions-post-delete-buttons';

    // custom options
    public bool $isPendingTable = true;

    public function builder(): Builder
    {
        return user()->pendingTodos()->getQuery();
    }
}
