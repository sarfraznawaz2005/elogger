<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;

class Replicate extends Component
{
    use InteractsWithBanner;

    public bool $isModalOpen = false;

    public string $replicateMessage = '';

    public function render(): Factory|View|Application
    {
        return view('livewire.replicate');
    }

    public function openModal(): void
    {
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
    }

    public function replicate(): void
    {
        try {
            $range = random_int(1, 5);
        } catch (\Exception) {
        }

        $arr = ['addMinutes', 'subMinute'];
        shuffle($arr);

        $pendingTodos = user()->pendingTodos;

        foreach ($pendingTodos as $pendingTodo) {
            $newTodo = $pendingTodo->replicate();
            $newTodo->dated = date('Y-m-d');
            $newTodo->time_start = date('H:i', strtotime(Carbon::parse($pendingTodo->time_start)->{$arr[0]}($range)));
            $newTodo->time_end = date('H:i', strtotime(Carbon::parse($pendingTodo->time_end)->{$arr[0]}($range)));
            $newTodo->description = $this->replicateMessage ?: $pendingTodo->description;
            $newTodo->save();
        }

        $this->closeModal();

        $this->banner('Replicated Successfully!');
    }
}
