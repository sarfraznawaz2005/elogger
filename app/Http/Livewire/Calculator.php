<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use JsonException;
use Livewire\Component;

class Calculator extends Component
{
    public array $items = [];

    public string $allowedLeaves = '72';
    public string $absents = '0';

    public string $months = '';

    public string $totalRequired = '0.00';
    public string $totalLogged = '0.00';
    public string $totalDiff = '0.00';
    public string $finalHours = '0.00';
    public string $hoursAvg = '0.00';

    protected array $rules = [
        'allowedLeaves' => 'required|integer|between:0,500',
        'absents' => 'required|integer|between:0,100',
        'items.*.working_days' => 'required|integer|between:0,31',
        'items.*.required_hours' => 'required|integer|between:0,500',
    ];

    protected array $messages = [
        'items.*.working_days.required' => 'Working Days field :index is required.',
        'items.*.working_days.integer' => 'Working Days field :index must be a number.',
        'items.*.working_days.between' => 'Working Days field :index value must be between 0 to 31.',
        'items.*.required_hours.required' => 'Required Hours field :index is required.',
        'items.*.required_hours.integer' => 'Required Hours field :index must be a number.',
        'items.*.required_hours.between' => 'Required Hours field :index value must be between 0 to 500.',
    ];

    /**
     * @throws JsonException
     */
    public function mount(): void
    {
        // defaults
        $this->items = [
            1 => ['month' => '', 'working_days' => '0', 'required_hours' => '0', 'logged_hours' => '0', 'diff' => '0'],
            2 => ['month' => '', 'working_days' => '0', 'required_hours' => '0', 'logged_hours' => '0', 'diff' => '0'],
            3 => ['month' => '', 'working_days' => '0', 'required_hours' => '0', 'logged_hours' => '0', 'diff' => '0'],
            4 => ['month' => '', 'working_days' => '0', 'required_hours' => '0', 'logged_hours' => '0', 'diff' => '0'],
            5 => ['month' => '', 'working_days' => '0', 'required_hours' => '0', 'logged_hours' => '0', 'diff' => '0'],
            6 => ['month' => '', 'working_days' => '0', 'required_hours' => '0', 'logged_hours' => '0', 'diff' => '0'],
        ];

        $this->months = json_encode([
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ], JSON_THROW_ON_ERROR);
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.calculator');
    }

    public function updated($propertyName): void
    {
        $this->validate();

        if ($propertyName !== 'allowedLeaves' && $propertyName !== 'absents') {

            $index = explode('.', $propertyName)[1];

            if (Str::contains($propertyName, '.month')) {

                if ($month = $this->items[$index]['month']) {

                    $this->items[$index]['working_days'] = $this->getWorkingDaysCount($this->items[$index]['month']);

                    $daysCount = cal_days_in_month(CAL_GREGORIAN, $month, date('Y'));

                    $this->items[$index]['logged_hours'] =
                        $this->getLoggedHoursTotal(getWorkedHoursDataForPeriod(date("Y-$month-1"), date("Y-$month-$daysCount")));

                } else {
                    $this->items[$index]['working_days'] = 0;
                    $this->items[$index]['logged_hours'] = 0;
                }

                $this->items[$index]['required_hours'] = $this->items[$index]['working_days'] * 8;
            }

            if (Str::contains($propertyName, '.working_days')) {
                if ($this->items[$index]['working_days'] < 0) {
                    $this->items[$index]['required_hours'] = 0;
                } else {
                    $this->items[$index]['required_hours'] = $this->items[$index]['working_days'] * 8;
                }
            }

            $this->items[$index]['diff'] = $this->items[$index]['logged_hours'] - $this->items[$index]['required_hours'];
        }

    }

    public function calculate(): void
    {
        dd($this->items);
    }

    // private functions

    private function getWorkingDaysCount($month): int
    {
        $workdays = [];

        $year = date('Y');

        $daysCount = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        // loop through all days
        for ($i = 1; $i <= $daysCount; $i++) {
            $date = $year . '/' . $month . '/' . $i; //format date
            $dayName = substr(date('l', strtotime($date)), 0, 3); // Trim day name to 3 chars

            //if not a weekend add day to array
            if ($dayName !== 'Sun' && $dayName !== 'Sat') {
                $workdays[] = $i;
            }
        }

        return count($workdays);
    }

    /** @noinspection ALL */
    private function getLoggedHoursTotal($xmlData): int|string
    {
        $hours = 0;

        if (isset($xmlData['time-entry'])) {

            // for when single record is returned
            $entry = (array)$xmlData['time-entry'];

            if (isset($entry['hours'])) {
                return round($entry['hours']);
            }

            foreach ($xmlData['time-entry'] as $timeEntryXML) {
                $array = (array)$timeEntryXML;

                if (isset($array['hours'])) {
                    $hours += $array['hours'];
                }
            }

            $hours = round($hours);
        }

        return $hours;
    }
}
