<?php

namespace App\Http\Livewire;

use App\Traits\InteractsWithFlash;
use Livewire\Component;

class Refresh extends Component
{
    use InteractsWithFlash;

    public bool $loading = true;

    /** @noinspection ALL */
    public function render(): string
    {
        if (session('month_hours')) {
            $this->loading = false;

            return '';
        }

        return <<<'blade'
            <div wire:init="refresh">
                <x-status-modal wire:model="loading">
                    Please wait while we are refreshing data once...
                </x-status-modal>
            </div>
        blade;
    }

    /** @noinspection ALL */
    public function refresh()
    {
        if (!session('month_hours')) {

            if (!checkConnection()) {
                $this->danger('We are unable to communicate with Basecamp API, make sure you are connected to internet & your settings are correct.');

                session(['month_hours' => 'none']);
                session()->put('not_connected', true);

                return redirect()->to('/');
            }

            session()->forget('not_connected');

            refreshData();
            //session(['month_hours' => 'none']);
            //session()->forget('app');

            $this->success('Data Refreshed Successfully!');

            return redirect()->to('/');
        }
    }
}
