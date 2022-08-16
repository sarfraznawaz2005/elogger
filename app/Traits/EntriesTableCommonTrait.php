<?php

namespace App\Traits;

use App\Models\Project;
use App\Models\Todo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\TimeColumn;

trait EntriesTableCommonTrait
{
    public function columns(): array
    {
        $columns = [

            //Column::name('id')->hide()->label('ID')->defaultSort('desc'),

            DateColumn::name('dated')->label('Date')
                ->width('130px')
                ->alignCenter()
                ->format('d M Y'),

            // causing search issue as well. tbf
            Column::callback('project_id', function ($project_id) {
                $limit = 20;

                $text = $this->projects->where('project_id', $project_id)->first()->project_name;

                if (strlen($text) <= $limit) {
                    return $text;
                }

                $textLimited = Str::limit($text, $limit);

                /** @noinspection ALL */
                return <<<html
                    <div class="inline" x-data="{tooltip: '$text'}">
                        <div x-tooltip="tooltip">$textLimited</div>
                    </div>
                html;
            })->label('Project')->alignCenter(),

            Column::callback('description', static function ($description) {
                $limit = 50;

                if (strlen($description) <= $limit) {
                    return $description;
                }

                $text = Str::limit($description, $limit);

                /** @noinspection ALL */
                return <<<html
                    <div class="inline" x-data="{tooltip: '$description'}">
                        <div x-tooltip="tooltip">$text</div>
                    </div>
                html;
            })->label('Description')->alignCenter(),

            TimeColumn::name('time_start')->label('Time Start')->width('125px')->alignCenter(),
            TimeColumn::name('time_end')->label('Time End')->width('125px')->alignCenter(),

            NumberColumn::callback(['dated', 'time_start', 'time_end'], static function ($dated, $time_start, $time_end) {
                $hours = getBCHoursDiff($dated, $time_start, $time_end);

                /** @noinspection ALL */
                return <<<html
                    <span class="hours bg-green-200 text-gray-700 text-md font-semibold px-2 py-1 rounded inline-block w-16">
                        $hours
                    </span>
                html;

            })->label('Total')->width('110px')->alignCenter(),

            Column::callback(['id', 'id'], function ($id) {
                return view('components.table-actions-entry', ['id' => $id, 'isPendingTable' => $this->isPendingTable]);
            })->label('Action')->width('160px')->alignCenter()->excludeFromExport(),
        ];

        // add select checkbox to pending table only
        if ($this->isPendingTable) {
            array_unshift($columns, Column::callback(['id'], static function ($id) {
                /** @noinspection ALL */
                return <<<html
                    <div>
                        <input type="checkbox" class="check-entry" value="$id"/>
                    </div>
                html;
            })->alignCenter()->width('50px')->excludeFromExport());
        }

        return $columns;
    }

    // The advantage of these computed properties is that they are cached between requests until page load.
    public function getProjectsProperty(): Collection
    {
        return Project::query()
            ->where('user_id', user()->id)
            ->get(['project_id', 'project_name']);
    }

    public function getTodosProperty(): Collection
    {
        return Todo::query()
            ->select('id', 'dated', 'time_start', 'time_end')
            ->where('user_id', user()->id)
            ->get();
    }
}
