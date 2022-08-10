<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use JsonException;
use Livewire\Component;

class Calculator extends Component
{
    public array $items = [];

    public int $allowedLeaves = 72;
    public string $months = '';

    /**
     * @throws JsonException
     */
    public function mount(): void
    {
        $this->items = [
            ['month' => '', 'working_days' => '0', 'required_hours' => '0', 'logged_hours' => '0', 'diff' => '0'],
            ['month' => '', 'working_days' => '0', 'required_hours' => '0', 'logged_hours' => '0', 'diff' => '0'],
            ['month' => '', 'working_days' => '0', 'required_hours' => '0', 'logged_hours' => '0', 'diff' => '0'],
            ['month' => '', 'working_days' => '0', 'required_hours' => '0', 'logged_hours' => '0', 'diff' => '0'],
            ['month' => '', 'working_days' => '0', 'required_hours' => '0', 'logged_hours' => '0', 'diff' => '0'],
            ['month' => '', 'working_days' => '0', 'required_hours' => '0', 'logged_hours' => '0', 'diff' => '0'],
        ];

        $this->months = json_encode([
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        ], JSON_THROW_ON_ERROR);
    }

    public function render(): Factory|View|Application
    {
        return view('livewire.calculator');
    }

    public function calculate(): void
    {
        dd($this->items);
    }
}
