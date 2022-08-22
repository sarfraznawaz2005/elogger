<?php

namespace App\Http\Livewire\Entries;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EntryStats extends Component
{
    protected $listeners = ['event-entries-updated' => '$refresh'];

    public function render(): Factory|View|Application
    {
        return view('livewire.entries.entry-stats');
    }
}
