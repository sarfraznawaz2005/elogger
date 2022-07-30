<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Refresh extends Component
{
    public bool $refreshing = true;

    public function render(): Factory|View|Application
    {
        if (session('month_hours')) {
            $this->refreshing = false;
        }

        return view('livewire.refresh');
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
