<?php

namespace App\Http\Livewire;

use App\Models\User;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class UsersDataTable extends LivewireDatatable
{
    public $model = User::class;

    public function columns(): array
    {
        return [
            Column::name('name')->searchable()->sortable(),
            Column::name('email')->searchable()->sortable(),
            BooleanColumn::name('is_admin')->sortable(),

            NumberColumn::callback(['id'], static function ($id) {
                $hours = (new User())->find($id)->pendingTodosHours();
                $hours = number_format($hours, 2);

                return view('components.table-actions-hours', ['hours' => $hours, 'color' => 'yellow']);
            })->label('Pending Hours')->sortable(),

            NumberColumn::callback(['id', 'id'], static function ($id) {
                $hours = (new User())->find($id)->postedTodosHours();
                $hours = number_format($hours, 2);

                return view('components.table-actions-hours', ['hours' => $hours, 'color' => 'green']);
            })->label('Posted Hours')->sortable(),
        ];
    }
}
