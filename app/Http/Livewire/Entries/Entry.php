<?php

namespace App\Http\Livewire\Entries;

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

    protected $listeners = [
        'onViewEntry' => 'onViewEntry',
        'onEditEntry' => 'onEditEntry',
        'view' => 'view',
        'edit' => 'edit',
        'onDeleteEntry' => 'delete',
    ];

    public array $todoLists = [];
    public array $todos = [];

    public ?array $item = null;

    // used for edits
    public int $itemId = 0;

    public bool $loading = false;
    public bool $disabled = false;

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

        return view('livewire.entries.entry', compact('projects'));
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

    public function create(): void
    {
        $this->disabled = false;

        // reset so that create form can be used again
        $this->itemId = 0;

        $this->openModal();
    }

    public function save(): void
    {
        //$isCreate = $this->itemId === 0;

        $data = $this->validate();
        $data = $data['item'];

        $data['user_id'] = user()->id;

        // make sure end time is greater than start time
        $diff = getBCHoursDiff($data['dated'], $data['time_start'], $data['time_end'], true);

        if ($diff < 0) {
            $this->dangerBanner('Start Time cannot be greater than End Time.');
            return;
        }

        /** @noinspection ALL */
        $todo = Todo::updateOrCreate(['id' => $this->itemId], $data);

        if (!$todo->save()) {
            $this->dangerBanner('Unable to save entry!');
            return;
        }

        $this->emit('event-entries-updated');
        $this->emit('refreshLivewireDatatable');

        $this->closeModal();

        $this->resetForm();

        $this->banner('Entry Saved Successfully!');
    }

    public function onViewEntry($id): void
    {
        $this->loading = true;
        $this->disabled = true;

        $this->emitSelf('view', $id);
    }

    public function onEditEntry($id): void
    {
        $this->loading = true;
        $this->disabled = false;

        $this->emitSelf('edit', $id);
    }

    /**
     * @throws JsonException
     */
    public function view($id): void
    {
        // because we have disabled fields using $disabled attribute
        $this->edit($id);
    }

    /**
     * @throws JsonException
     */
    public function edit($id): void
    {
        // clear validation messages
        $this->resetErrorBag();

        /** @noinspection ALL */
        $todo = Todo::findOrFail($id);

        $this->itemId = $id;
        $this->name = $todo->name;

        $this->item['project_id'] = $todo->project_id;
        $this->item['todolist_id'] = $todo->todolist_id;
        $this->item['todo_id'] = $todo->todo_id;
        $this->item['dated'] = $todo->dated;
        $this->item['time_start'] = $todo->time_start;
        $this->item['time_end'] = $todo->time_end;
        $this->item['description'] = $todo->description;

        $this->todoLists = json_decode($this->todoLists($todo->project_id), true, 512, JSON_THROW_ON_ERROR);
        $this->todos = json_decode($this->todos($todo->todolist_id), true, 512, JSON_THROW_ON_ERROR);

        $this->loading = false;

        $this->openModal();
    }

    public function delete($id): void
    {
        /** @noinspection ALL */
        if (Todo::find($id)->delete()) {
            $this->banner('Entry Deleted Successfully!');

            $this->emit('refreshLivewireDatatable');
            $this->emit('event-entries-updated');
        }
    }

    public function resetForm(): void
    {
        unset(
            $this->item['project_id'],
            $this->item['todolist_id'],
            $this->item['todo_id'],
            $this->item['dated'],
            $this->item['time_start'],
            $this->item['time_end'],
            $this->item['description']
        );
    }
}
