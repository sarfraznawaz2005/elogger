<?php

namespace App\Http\Livewire\DataTables;

use App\Models\Todo;
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

    public $beforeTableSlot = 'components.table-actions-post-delete-buttons';

    // custom options
    public bool $hidePageSize = false;
    public bool $isPendingTable = false;
    public string $totalSlot = 'components.table-totals';

    public function builder(): Builder
    {
        //return user()->postedTodos()->getQuery();
        return Todo::query()
            ->where('todos.user_id', user()->id)
            ->where('status', 'posted');
    }
}
