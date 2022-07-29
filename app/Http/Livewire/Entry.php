<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use JsonException;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;

class Entry extends Component
{
    use InteractsWithBanner;

    public array $todoLists = [];
    public array $todos = [];

    public ?string $project_id = null;
    public ?string $todolist_id = null;
    public ?string $todo_id = null;

    public ?array $item = null;

    public bool $isModalOpen = false;

    protected array $rules = [
        'item.project_id' => 'required',
        'item.todolist_id' => 'required',
        'item.todo_id' => 'required',
        'item.dated' => 'required',
        'item.time_start' => 'required',
        'item.time_end' => 'required',
        'item.description' => 'required',
    ];

    protected array $messages = [
        'item.project_id.required' => 'This field is required.',
        'item.todolist_id.required' => 'This field is required.',
        'item.todo_id.required' => 'This field is required.',
        'item.dated.required' => 'This field is required.',
        'item.time_start.required' => 'This field is required.',
        'item.time_end.required' => 'This field is required.',
        'item.description.required' => 'This field is required.',
    ];

    public function boot(): void
    {
        $this->item['user_id'] = user()->id;
    }

    /**
     * @return Factory|View|Application
     */
    public function render(): Factory|View|Application
    {
        $projects = user()->projectsAll->pluck('project_name', 'project_id')->toArray();
        asort($projects);

        return view('livewire.entry', compact('projects'));
    }

    /**
     * @throws JsonException
     */
    public function updatedProjectId(): void
    {
        if ($this->project_id) {

            $this->item['project_id'] = $this->project_id;

            $this->todoLists = [];
            $this->todos = [];
            $this->todolist_id = null;
            $this->todo_id = null;

            $this->todoLists = json_decode($this->todoLists($this->project_id), true, 512, JSON_THROW_ON_ERROR);
        } else {
            $this->item['project_id'] = null;
        }
    }

    /**
     * @throws JsonException
     */
    public function updatedTodolistId(): void
    {
        if ($this->todolist_id) {
            $this->item['todolist_id'] = $this->todolist_id;

            $this->todos = [];
            $this->todo_id = null;

            $this->todos = json_decode($this->todos($this->todolist_id), true, 512, JSON_THROW_ON_ERROR);
        } else {
            $this->item['todolist_id'] = null;
        }
    }

    public function updatedTodoId(): void
    {
        if ($this->todo_id) {
            $this->item['todo_id'] = $this->todo_id;
        } else {
            $this->item['todo_id'] = null;
        }
    }

    /**
     * @throws JsonException
     */
    public function todoLists($projectId): bool|string
    {
        try {
            return json_encode(getProjectTodoLists($projectId), JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return json_encode([], JSON_THROW_ON_ERROR);
        }
    }

    /**
     * @throws JsonException
     */
    public function todos($todolistId): bool|string
    {
        try {
            return json_encode(getTodoListTodos($todolistId), JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return json_encode([], JSON_THROW_ON_ERROR);
        }
    }

    public function openModal(): void
    {
        $this->isModalOpen = true;
    }

    public function closeModal(): void
    {
        $this->isModalOpen = false;
    }

    public function create(): void
    {
        $this->validate();
    }
}
