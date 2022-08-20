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
        if (session('month_hours') || !hasBasecampSetup()) {
            $this->loading = false;

            # important otherwise component loaded without need
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
        if (!checkConnection()) {
            $this->danger('We are unable to communicate with Basecamp!');

            session()->put('month_hours', 'none');
            session()->put('not_connected', true);

            // strange but needed this otherwise session was not being set
            // most likely livewire was refreshing page quickly again.
            sleep(1);

            return redirect()->to('/');
        }

        session()->forget('not_connected');

        if (!session('month_hours')) {
            refreshData();
            //session(['month_hours' => 30]);

            $this->success('Data Refreshed Successfully!');

            return redirect()->to('/');
        }
    }
}
