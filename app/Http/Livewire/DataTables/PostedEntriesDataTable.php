<?php

namespace App\Http\Livewire\DataTables;

use App\Traits\EntriesTableCommonTrait;
use Illuminate\Database\Eloquent\Builder;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;

class PostedEntriesDataTable extends LivewireDatatable
{
    use EntriesTableCommonTrait;

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
    public bool $isPendingTable = false;

    public function builder(): Builder
    {
        return user()->postedTodos()->getQuery();
    }
}