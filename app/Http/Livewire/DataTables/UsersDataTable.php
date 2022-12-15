<?php

namespace App\Http\Livewire\DataTables;

use App\Models\User;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

/** @noinspection ALL */

class UsersDataTable extends LivewireDatatable
{
    public $model = User::class;

    // table specific options
    public $persistSearch = false;
    public $persistComplexQuery = false;
    public $persistHiddenColumns = false;
    public $persistSort = false;
    public $persistFilters = false;

    public bool $hidePageSize = false;

    /** @noinspection ALL */
    public function columns(): array
    {
        $modelInstance = null;

        $columns = [

            NumberColumn::callback(['id'], function ($id) use (&$modelInstance) {
                $modelInstance = $this->model::find($id);
            })->hide(),

            Column::name('name')->label('Name')->defaultSort('asc')->sortable(),
            Column::name('email')->label('Email'),
            BooleanColumn::name('is_admin')->label('Admin')->sortable()->alignCenter(),

            NumberColumn::callback(['id', 'id'], static function ($id) use (&$modelInstance) {
                $hours = number_format($modelInstance->pendingTodosHoursMonth($id), 2);

                return <<<html
                    <span class="yellow-light-box text-gray-700 text-md font-semibold px-2 py-1 rounded w-20 inline-block">
                        $hours
                    </span>
                html;

            })->label('Pending Hours')->alignCenter(),

            NumberColumn::callback(['id', 'id', 'id', 'id'], static function ($id) use (&$modelInstance) {

                if (!hasBasecampSetup($modelInstance)) {
                    return <<<html
                        <span class="green-light-box text-gray-700 text-md font-semibold px-2 py-1 rounded w-20 inline-block">
                            0.00
                        </span>
                    html;
                }

                $hours = getUserMonthUploadedHours($modelInstance->basecamp_api_user_id, true);
                $hours = number_format($hours, 2);

                return <<<html
                    <span class="green-light-box text-gray-700 text-md font-semibold px-2 py-1 rounded w-20 inline-block">
                        $hours
                    </span>
                html;

            })->label('Uploaded Hours')->alignCenter(),

            Column::callback(['basecamp_api_user_id', 'holidays_count', 'working_hours_count'],
                static function ($bcId, $holidaysCount, $workingHoursCount) use (&$modelInstance) {
                    $workDayCountMonth = workDayCountMonth($holidaysCount);

                    $hoursTotal = workMonthRequiredHours($workDayCountMonth, $workingHoursCount);
                    $hoursProjected = monthProjectedHours($workDayCountMonth, $holidaysCount, true, $bcId, $workingHoursCount, $modelInstance);

                    return <<<html
                    <span class="blue-light-box text-gray-700 text-md font-semibold px-2 py-1 rounded w-24 inline-block">
                        $hoursProjected of $hoursTotal
                    </span>
                html;

                })->label('Month Projection')->alignCenter(),
        ];

        if (user()->isAdmin()) {
            $columns[] = Column::callback(['id', 'email'], function ($id, $email) {

                if ($email === auth()->user()->email) {
                    return 'N/A';
                }

                return view('components.table-actions-user', ['id' => $id]);

            })->label('Action')->width('160px')->alignCenter()->excludeFromExport();
        }

        return $columns;
    }
}
