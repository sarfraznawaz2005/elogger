<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class RefreshDashboard extends Component
{
    public function render(): Factory|View|Application
    {
        return view('livewire.refresh-dashboard');
    }

    public function refresh()
    {
        refreshData();

        return redirect()->to('/');
    }
}
