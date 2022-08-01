<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Refresh extends Component
{
    public bool $loading = true;

    /** @noinspection ALL */
    public function render(): string
    {
        if (session('month_hours')) {
            $this->loading = false;
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
            refreshData();

            return redirect()->to('/');
        }
    }
}
