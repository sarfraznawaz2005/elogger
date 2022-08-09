<?php

namespace App\Http\Livewire\Entries;

use App\Traits\InteractsWithModal;
use App\Traits\InteractsWithToast;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Replicate extends Component
{
    use InteractsWithModal;
    use InteractsWithToast;

    public string $replicateMessage = '';

    public function render(): Factory|View|Application
    {
        return view('livewire.entries.replicate');
    }

    /** @noinspection ALL */
    public function replicate(): void
    {
        try {
            $range = random_int(1, 5);
        } catch (Exception) {
        }

        $arr = ['addMinutes', 'subMinute'];
        shuffle($arr);

        $pendingTodos = user()->pendingTodos;

        if (!$pendingTodos->count()) {
            $this->closeModal();
            $this->danger('There are no pending entries!');
            return;
        }

        foreach ($pendingTodos as $pendingTodo) {
            $newTodo = $pendingTodo->replicate();
            $newTodo->dated = date('Y-m-d');
            $newTodo->time_start = date('H:i', strtotime(Carbon::parse($pendingTodo->time_start)->{$arr[0]}($range)));
            $newTodo->time_end = date('H:i', strtotime(Carbon::parse($pendingTodo->time_end)->{$arr[0]}($range)));
            $newTodo->description = trim($this->replicateMessage) ?: $pendingTodo->description;
            $newTodo->save();
        }

        $this->emit('event-entries-updated');
        $this->emit('refreshLivewireDatatable');

        $this->closeModal();

        $this->success('Replicated Successfully!');
    }
}
