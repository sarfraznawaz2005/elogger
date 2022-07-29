<?php

namespace App\Http\Livewire;

use App\Services\Data;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Graphs extends Component
{
    public function render(): Factory|View|Application
    {
        $allUsersHours = [];
        $projects = collect(Data::getUserProjectlyHours())->sortByDesc('hours');

        if (session('all_users_hours') && user()->isAdmin()) {
            $allUsersHours = session('all_users_hours');
        }

        return view('livewire.graphs', compact('projects', 'allUsersHours'));
    }
}
