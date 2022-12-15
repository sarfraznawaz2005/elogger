<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use App\Traits\InteractsWithToast;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class IndexUsers extends Component
{
    use InteractsWithToast;

    protected $listeners = [
        'delete',
    ];

    // doing this because this page takes some time to load due to calculations
    public bool $loading = true;

    public function render(): Factory|View|Application
    {
        abort_if(!user()->isAdmin(), 403);

        return view('livewire.users.index');
    }

    public function load(): void
    {
        $this->loading = false;
    }

    public function delete(User $user): void
    {
        if ($user->delete()) {
            $this->emit('refreshLivewireDatatable');
            $this->success('User Deleted Successfully!');
        } else {
            $this->danger('Unable to delete user!');
        }
    }
}
