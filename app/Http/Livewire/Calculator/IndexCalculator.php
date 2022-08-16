<?php

namespace App\Http\Livewire\Calculator;

use App\Traits\InteractsWithToast;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use JsonException;
use Livewire\Component;

class IndexCalculator extends Component
{
    use InteractsWithToast;

    public array $items = [];

    public string $allowedLeaves = '72';
    public string $absents = '0';
    public string $year = '';

    public string $totalRequired = '0';
    public string $totalLogged = '0';
    public string $totalDiff = '0';
    public string $finalHours = '0';
    public string $hoursAvg = '0.00';

    public string $months = '';
    public array $years = [];

    protected array $rules = [
        'allowedLeaves' => 'required|integer|between:0,500',
        'absents' => 'required|integer|between:0,100',
        'items.*.working_days' => 'sometimes|integer|between:1,31',
        'items.*.required_hours' => 'sometimes|integer',
    ];

    protected array $validationAttributes = [
        'allowedLeaves' => 'Allowed Leaves Hours',
        'absents' => 'Total Absents',
    ];

    protected array $messages = [
        'items.*.working_days.required' => 'Working Days field :index is required.',
        'items.*.working_days.integer' => 'Working Days field :index must be a number.',
        'items.*.working_days.between' => 'Working Days field :index value must be between 1 to 31.',
        'items.*.required_hours.required' => 'Required Hours field :index is required.',
        'items.*.required_hours.integer' => 'Required Hours field :index must be a number.',
    ];

    /**
     * @throws JsonException
     */
    public function mount(): void
    {
        // defaults
        $this->items = [
            1 => ['month' => '', 'working_days' => '', 'required_hours' => '', 'logged_hours' => '0', 'diff' => '0'],
            2 => ['month' => '', 'working_days' => '', 'required_hours' => '', 'logged_hours' => '0', 'diff' => '0'],
            3 => ['month' => '', 'working_days' => '', 'required_hours' => '', 'logged_hours' => '0', 'diff' => '0'],
            4 => ['month' => '', 'working_days' => '', 'required_hours' => '', 'logged_hours' => '0', 'diff' => '0'],
            5 => ['month' => '', 'working_days' => '', 'required_hours' => '', 'logged_hours' => '0', 'diff' => '0'],
            6 => ['month' => '', 'working_days' => '', 'required_hours' => '', 'logged_hours' => '0', 'diff' => '0'],
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

        $this->year = date('Y');

        // add few years in past and future
        for ($i = (int)date('Y') - 3; $i <= (int)date('Y'); $i++) {
            $this->years[$i] = $i;
        }

        ################################################################
        # GET SAVED DATA FROM DB IF ANY
        ################################################################

        if (user()->calculations) {
            $data = json_decode(user()->calculations, false, 512, JSON_THROW_ON_ERROR);

            $this->year = $data->year ?: $this->year;
            $this->absents = $data->absents ?: '0';
            $this->allowedLeaves = $data->allowed_leaves ?: '72';
            $this->items = json_decode(json_encode($data->items, JSON_THROW_ON_ERROR), true, 512, JSON_THROW_ON_ERROR);

            $this->calculate();
        }

    }

    public function render(): Factory|View|Application
    {
        return view('livewire.calculator.index');
    }

    /**
     * @throws ValidationException
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);

        $excludedProperties = [
            'year',
            'absents',
            'allowedLeaves',
        ];

        if (!in_array($propertyName, $excludedProperties, true)) {

            $workingHoursCount = (int)user()->working_hours_count ?: 8;

            $index = explode('.', $propertyName)[1];

            if (Str::contains($propertyName, '.month')) {

                if ($month = $this->items[$index]['month']) {

                    $this->items[$index]['working_days'] = $this->getWorkingDaysCount($this->items[$index]['month']);

                    $daysCount = $this->daysInMonth($month, $this->year);

                    $this->items[$index]['logged_hours'] =
                        $this->getLoggedHoursTotal(getWorkedHoursDataForPeriod(date("$this->year-$month-1"), date("$this->year-$month-$daysCount")));

                } else {
                    $this->items[$index]['diff'] = '0';
                    $this->items[$index]['logged_hours'] = '0';
                    $this->items[$index]['working_days'] = '';
                    $this->items[$index]['required_hours'] = '';
                }

                if ($this->items[$index]['working_days']) {
                    $this->items[$index]['required_hours'] = $this->items[$index]['working_days'] * $workingHoursCount;
                }
            }

            if (Str::contains($propertyName, '.working_days')) {
                if ($this->items[$index]['working_days'] < 0) {
                    $this->items[$index]['required_hours'] = '';
                } else {
                    $this->items[$index]['required_hours'] = $this->items[$index]['working_days'] * $workingHoursCount;
                }
            }

            if ($this->items[$index]['logged_hours'] && $this->items[$index]['required_hours']) {
                $this->items[$index]['diff'] = $this->items[$index]['logged_hours'] - $this->items[$index]['required_hours'];
            }
        }

        $this->calculate();
    }

    private function calculate(): void
    {
        $this->validate();

        $items = collect($this->items)->where('month', '!=', '');

        // set default of 0 if empty
        $items = $items->map(function ($item) {
            if (!$item['working_days']) {
                $item['working_days'] = 0;
            }

            if (!$item['required_hours']) {
                $item['required_hours'] = 0;
            }

            if (!$item['diff']) {
                $item['diff'] = 0;
            }

            return $item;
        });

        // for final hours
        $this->totalRequired = round($items->sum('required_hours'));
        $this->totalLogged = round($items->sum('logged_hours'));
        $this->totalDiff = round($items->sum('diff'));

        $this->finalHours = round($this->totalDiff + (int)$this->allowedLeaves);

        // for hours avg
        $workingDaysSum = $items->sum('working_days');

        if ($workingDaysSum - (int)$this->absents > 0) {
            $this->hoursAvg = number_format($this->totalLogged / ($workingDaysSum - (int)$this->absents), 2);
        } else {
            $this->hoursAvg = '0.00';
        }
    }

    /**
     * @throws JsonException
     */
    public function save(): void
    {
        $this->calculate(); // to validate and filter out invalid data

        $data = [
            'items' => $this->items,
            'year' => $this->year,
            'absents' => $this->absents,
            'allowed_leaves' => $this->allowedLeaves,
        ];

        $updated = user()->update(['calculations' => json_encode($data, JSON_THROW_ON_ERROR)]);

        if ($updated) {
            $this->success('Calculations Saved Successfully!');
        } else {
            $this->danger('Calculations Could Not Be Saved!');
        }
    }

    ################################################################
    # PRIVATE FUNCTIONS
    ################################################################

    /** @noinspection ALL */
    private function daysInMonth($month, $year): int
    {
        if (extension_loaded('calendar')) {
            return cal_days_in_month(CAL_GREGORIAN, $month, $year);
        }

        return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
    }

    private function getWorkingDaysCount($month): int
    {
        $workdays = [];

        $year = $this->year;

        $daysCount = $this->daysInMonth($month, $year);

        for ($i = 1; $i <= $daysCount; $i++) {
            $date = $year . '/' . $month . '/' . $i;
            $dayName = substr(date('l', strtotime($date)), 0, 3);

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
