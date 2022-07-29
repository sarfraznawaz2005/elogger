<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Sushi\Sushi;

class BasecampProject extends Model
{
    use Sushi;

    public $incrementing = false;

    protected array $schema = [
        'id' => 'integer',
        'person-id' => 'integer',
        'project-id' => 'integer',
        'todo-item-id' => 'integer',
        'date' => 'date',
        'description' => 'string',
        'hours' => 'float',
        'person-name' => 'string',
    ];

    public function getRows(): array
    {
        $projectsData = [];

        $data = getWorkedHoursData();

        if (isset($data['time-entry'])) {
            foreach ($data['time-entry'] as $timeEntryXML) {
                $array = (array)$timeEntryXML;
                $projectsData[] = $array;
            }
        }

        $array = collect($projectsData)->where('project-id', session('project_id'))->all();

        return array_values($array);
    }
}
