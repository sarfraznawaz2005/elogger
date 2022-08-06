<?php

namespace App\Http\Livewire\Users;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class IndexUsers extends Component
{
    // doing this because this page takes some time to load due to calculations
    public bool $loading = true;

    public function render(): Factory|View|Application
    {
        return view('livewire.users.index');
    }

    public function load(): void
    {
        $this->loading = false;
    }
}
