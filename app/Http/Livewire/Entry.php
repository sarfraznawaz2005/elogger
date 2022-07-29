<?php

namespace App\Http\Livewire;

use App\Models\Todo;
use App\Traits\InteractsWithModal;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\ValidationException;
use JsonException;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;

class Entry extends Component
{
    use InteractsWithBanner;
    use InteractsWithModal;

    public array $todoLists = [];
    public array $todos = [];

    public ?array $item = null;

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
     * @throws JsonException|ValidationException
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);

        if (($propertyName === 'item.project_id') && $this->item['project_id']) {
            $this->todoLists = [];
            $this->todos = [];
            $this->item['todolist_id'] = null;
            $this->item['todo_id'] = null;

            $this->todoLists = json_decode($this->todoLists($this->item['project_id']), true, 512, JSON_THROW_ON_ERROR);
        }

        if ($propertyName === 'item.todolist_id' && $this->item['todolist_id']) {
            $this->todos = [];
            $this->item['todo_id'] = null;

            $this->todos = json_decode($this->todos($this->item['todolist_id']), true, 512, JSON_THROW_ON_ERROR);
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

    public function create(Todo $todo): void
    {
        $data = $this->validate();
        $data = $data['item'];

        $data['user_id'] = user()->id;

        // make sure end time is greater than start time
        $diff = getBCHoursDiff($data['dated'], $data['time_start'], $data['time_end'], true);

        if ($diff < 0) {
            $this->dangerBanner('Start Time cannot be greater than End Time.');
        }

        $todo->fill($data);

        if (!$todo->save()) {
            $this->dangerBanner('Unable to save entry!');
        }

        session(['project_id' => $data['project_id']]);
        session(['todolist_id' => $data['todolist_id']]);
        session(['todo_id' => $data['todo_id']]);
        session(['description' => $data['description']]);

        $this->closeModal();

        $this->banner('Entry Saved Successfully!');
    }
}
