<?php

namespace App\Traits;

use App\Models\Project;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\TimeColumn;

trait EntriesTableCommonTrait
{
    public function columns(): array
    {
        $projects = Project::query()
            ->select(['project_id', 'project_name'])
            ->where('user_id', user()->id)
            ->get();

        $columns = [

            //Column::name('id')->hide()->label('ID')->defaultSort('desc'),

            DateColumn::callback(['dated'], static function ($date) {
                $color = 'gray';
                $date = date('d M Y', strtotime($date));

                if (Carbon::parse($date)->isToday()) {
                    $color = 'green';
                }

                return <<<html
                    <span class="bg-$color-200 text-gray-700 text-md font-semibold px-2 py-1 rounded inline-block w-24">
                        $date
                    </span>
                html;
            })->label('Date')->width('1px')->alignCenter(),

            // causing search issue as well. tbf
            Column::callback('project_id', static function ($project_id) use ($projects) {
                $limit = 20;

                $text = $projects->where('project_id', $project_id)->first()->project_name;

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

            TimeColumn::callback(['time_start'], static function ($time_start) {
                $time = date('h:i A', strtotime($time_start));

                return <<<html
                    <span class="bg-yellow-200 text-gray-700 text-md font-semibold px-2 py-1 rounded inline-block w-20">
                        $time
                    </span>
                html;
            })->label('Time Start')->width('1px')->alignCenter(),

            TimeColumn::callback(['time_end'], static function ($time_end) {
                $time = date('h:i A', strtotime($time_end));

                return <<<html
                    <span class="bg-yellow-200 text-gray-700 text-md font-semibold px-2 py-1 rounded inline-block w-20">
                        $time
                    </span>
                html;
            })->label('Time End')->width('1px')->alignCenter(),

            NumberColumn::callback(['dated', 'time_start', 'time_end'], static function ($dated, $time_start, $time_end) {
                $hours = getBCHoursDiff($dated, $time_start, $time_end);

                return <<<html
                    <span class="hours bg-green-200 text-gray-800 text-md font-bold px-2 py-1 rounded inline-block w-16">
                        $hours
                    </span>
                html;

            })->label('Total')->width('1px')->alignCenter(),

            Column::callback(['id', 'id'],
                fn($id) => view('components.table-actions-entry', ['id' => $id, 'isPendingTable' => $this->isPendingTable])
            )->label('Action')->width('160px')->alignCenter()->excludeFromExport(),
        ];

        // add select checkbox to pending table only
        if ($this->isPendingTable) {
            array_unshift($columns, Column::callback(['id'], static fn($id) => <<<html
                    <input type="checkbox" class="check-entry" value="$id"/>
                html
            )->alignCenter()->width('1px')->excludeFromExport());
        }

        return $columns;
    }
}
