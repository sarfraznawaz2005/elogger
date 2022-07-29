<?php

namespace App\Http\Livewire;

use App\Traits\InteractsWithEvents;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EntryStats extends Component
{
    use InteractsWithEvents;

    protected $listeners = ['event-entry-saved' => 'onEvent'];

    public function render(): Factory|View|Application
    {
        $PendingTodosHoursToday = number_format(user()->pendingTodosHoursToday(), 2);
        $PendingTodosHoursTotal = number_format(user()->pendingTodosHours(), 2);
        $PostedTodosHoursTotal = number_format(user()->postedTodosHours(), 2);

        return view(
            'livewire.entry-stats',
            compact('PendingTodosHoursToday', 'PendingTodosHoursTotal', 'PostedTodosHoursTotal')
        );
    }
}
