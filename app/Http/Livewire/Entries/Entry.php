<?php

namespace App\Http\Livewire\Entries;

use App\Models\Todo;
use App\Traits\InteractsWithModal;
use App\Traits\InteractsWithToast;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use JsonException;
use Livewire\Component;

class Entry extends Component
{
    use InteractsWithToast;
    use InteractsWithModal;

    protected $listeners = [
        'onDuplicateEntry' => 'onDuplicateEntry',
        'onEditEntry' => 'onEditEntry',
        'edit' => 'edit',
        'duplicate' => 'duplicate',
        'onDeleteEntry' => 'delete',
        'onDeleteAllPosted' => 'deleteAllPosted',
        'onDeleteSelected' => 'deleteSelected',
        'onUploadSelected' => 'uploadSelected',
        'onDeleteFromBasecamp' => 'deleteFromBasecamp',
    ];

    public Todo $model;

    // data needed on form
    public array $todoLists = [];
    public array $todos = [];

    // others
    public string $timeTotal = '0.00';
    public bool $loading = false;
    public string $modalTitle = 'Time Entry';

    protected array $rules = [
        'model.project_id' => 'required',
        'model.todolist_id' => 'required',
        'model.todo_id' => 'required',
        'model.dated' => 'required',
        'model.time_start' => 'required',
        'model.time_end' => 'required',
        'model.description' => 'required|min:5',
    ];

    protected array $messages = [
        'model.project_id.required' => 'This field is required.',
        'model.todolist_id.required' => 'This field is required.',
        'model.todo_id.required' => 'This field is required.',
        'model.dated.required' => 'This field is required.',
        'model.time_start.required' => 'This field is required.',
        'model.time_end.required' => 'This field is required.',
        'model.description.required' => 'This field is required.',
        'model.description.min' => 'Must be minimum 5 characters.',
    ];

    public function booted(): void
    {
        $this->timeTotal = '0.00';
    }

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
     * @throws JsonException
     */
    public function updated($propertyName): void
    {
        if ($propertyName === 'model.project_id') {
            $this->todoLists = [];
            $this->todos = [];
            $this->model->todolist_id = null;
            $this->model->todo_id = null;

            if ($this->model->project_id) {
                $this->todoLists = json_decode($this->fetchTodoLists($this->model->project_id), true, 512, JSON_THROW_ON_ERROR);
            }
        }

        if ($propertyName === 'model.todolist_id') {
            $this->todos = [];
            $this->model->todo_id = null;

            if ($this->model->todolist_id) {
                $this->todos = json_decode($this->fetchTodos($this->model->todolist_id), true, 512, JSON_THROW_ON_ERROR);
            }
        }

        if ($propertyName === 'model.time_start' || $propertyName === 'model.time_end') {
            $this->timeTotal = getBCHoursDiff($this->model->dated, $this->model->time_start, $this->model->time_end);
        }
    }

    /** @noinspection ALL */
    public function onDuplicateEntry(Todo $todo): void
    {
        $this->loading = true;

        $this->emitSelf('duplicate', $todo);
    }

    /** @noinspection ALL */
    public function onEditEntry(Todo $todo): void
    {
        $this->loading = true;

        $this->emitSelf('edit', $todo);
    }

    /** @noinspection ALL */
    public function onDeleteFromBasecamp(Todo $todo): void
    {
        $this->loading = true;

        $this->emitSelf('deleteFromBasecamp', $todo);
    }

    public function create(): void
    {
        $this->model = new Todo();
        $this->model->dated = date('Y-m-d');
        $this->model->time_start = $this->model->time_end = date('H:i');

        $this->modalTitle = 'Add Entry';

        $this->clearValidation();
        $this->openModal();
    }

    /**
     * @throws JsonException
     */
    public function duplicate(Todo $todo): void
    {
        $this->model = new Todo();

        $this->model->project_id = $todo->project_id;
        $this->model->todolist_id = $todo->todolist_id;
        $this->model->todo_id = $todo->todo_id;

        $this->model->description = '';
        $this->model->dated = date('Y-m-d');
        $this->model->time_start = $this->model->time_end = date('H:i');

        $this->todoLists = json_decode($this->fetchTodoLists($this->model->project_id), true, 512, JSON_THROW_ON_ERROR);
        $this->todos = json_decode($this->fetchTodos($this->model->todolist_id), true, 512, JSON_THROW_ON_ERROR);

        $this->modalTitle = 'Add Entry';

        $this->clearValidation();
        $this->openModal();

        $this->loading = false;
    }

    /**
     * @throws JsonException
     */
    public function edit(Todo $todo): void
    {
        $this->model = $todo;

        $this->todoLists = json_decode($this->fetchTodoLists($this->model->project_id), true, 512, JSON_THROW_ON_ERROR);
        $this->todos = json_decode($this->fetchTodos($this->model->todolist_id), true, 512, JSON_THROW_ON_ERROR);

        $this->timeTotal = getBCHoursDiff($this->model->dated, $this->model->time_start, $this->model->time_end);

        $this->modalTitle = 'Edit Entry';

        $this->clearValidation();
        $this->openModal();

        $this->loading = false;
    }

    public function save(): void
    {
        $isCreate = is_null($this->model->id);

        $this->validate();

        $this->model->user_id = user()->id;

        // make sure end time is greater than start time
        $diff = getBCHoursDiff($this->model->dated, $this->model->time_start, $this->model->time_end, true);

        if ($diff < 0) {
            $this->danger('Start Time cannot be greater than End Time.');
            return;
        }

        if ($diff === '0' || $diff === '0.00') {
            $this->danger('Start Time and End Time cannot be same.');
            return;
        }

        if (!$this->model->save()) {
            $this->danger('Unable to save entry!');
            return;
        }

        $this->emit('event-entries-updated');
        $this->emit('refreshLivewireDatatable');

        $this->closeModal();

        if ($isCreate) {
            $this->success('Entry Created Successfully!');
        } else {
            $this->success('Entry Updated Successfully!');
        }
    }

    public function delete(Todo $todo): void
    {
        if ($todo->delete()) {
            $this->emit('refreshLivewireDatatable');
            $this->emit('event-entries-updated');

            $this->success('Entry Deleted Successfully!');
        } else {
            $this->danger('Unable to delete entry!');
        }
    }

    /** @noinspection ALL */
    public function deleteAllPosted(): void
    {
        if (Todo::whereStatus('posted')->delete()) {
            $this->emit('refreshLivewireDatatable');
            $this->emit('event-entries-updated');

            $this->success('All Posted Entries Deleted Successfully!');
        } else {
            $this->danger('Unable to delete entries!');
        }
    }

    public function deleteSelected($ids): void
    {
        if (Todo::query()->whereIn('id', $ids)->delete()) {
            $this->emit('refreshLivewireDatatable');
            $this->emit('event-entries-updated');

            $this->success('Selected Entries Deleted Successfully!');
        } else {
            $this->danger('Unable to delete entries!');
        }
    }

    public function uploadSelected($ids): void
    {
        set_time_limit(0);

        $posted = '';

        Todo::query()->whereIn('id', $ids)->chunk(50, function ($todos) use (&$posted) {
            foreach ($todos as $todo) {
                $personId = user()->basecamp_api_user_id;
                $hours = getBCHoursDiff($todo->dated, $todo->time_start, $todo->time_end);
                $projectName = $todo->project->project_name;

                // find out action endpoint to post to basecamp
                $action = 'projects/' . $todo->project_id . '-' . Str::slug($projectName) . '/time_entries.xml';

                $xmlData = <<<data
                        <time-entry>
                          <date>$todo->dated</date>
                          <description>$todo->description</description>
                          <hours>$hours</hours>
                          <person-id>$personId</person-id>
                          <todo-item-id>$todo->todo_id</todo-item-id>
                        </time-entry>
                data;

                // send to basecamp
                $response = postInfo($action, $xmlData);

                // check to see if it was posted successfully to BC
                if ($response && $response['code'] === 201) {

                    // get created item id
                    $timeEntryId = getResourceCreatedId($response['content']);

                    // update entry with basecamp item id
                    if ($timeEntryId) {
                        $todo->time_id = $timeEntryId;
                    }

                    $todo->status = 'posted';
                    $todo->save();

                    $posted = 'ok';
                } else {
                    $this->success('Entry "' . $todo->description . '" with hours of ' . $hours . ' could not be posted.');
                }

                // so that we do not send post request too fast to BC
                sleep(1);
            }
        });

        if ($posted === 'ok') {
            $monthHours = getUserMonthlyHours();
            session(['month_hours' => $monthHours]);

            $this->emit('refreshLivewireDatatable');
            $this->emit('event-entries-updated');

            $this->success('Selected Entries Uploaded Successfully!');
        }
    }

    /** @noinspection ALL */
    public function deleteFromBasecamp(Todo $todo): void
    {
        if (!$todo->time_id) {
            $this->danger('This Entry Cannot Be Deleted From Basecamp!');
            return;
        }

        $responseCode = deleteResource("time_entries/" . $todo->time_id);

        if ($responseCode !== 200) {
            $this->danger('Entry Could Not Be Deleted From Basecamp!');
            return;
        }

        if ($todo->delete()) {
            $this->emit('refreshLivewireDatatable');
            $this->emit('event-entries-updated');

            $this->success('Entry Deleted Successfully!');
        }

        $this->loading = false;
    }


    /**
     * @throws JsonException
     */
    public function fetchTodoLists($projectId): bool|string
    {
        try {

            if (session()->has('app.todo-list-' . $projectId)) {
                return session('app.todo-list-' . $projectId);
            }

            $todoLists = json_encode(getProjectTodoLists($projectId), JSON_THROW_ON_ERROR);

            if ($todoLists) {
                session()->put('app.todo-list-' . $projectId, $todoLists);
            }

            return $todoLists;

        } catch (JsonException) {
            return json_encode([], JSON_THROW_ON_ERROR);
        }
    }

    /**
     * @throws JsonException
     */
    public function fetchTodos($todolistId): bool|string
    {
        try {

            if (session()->has('app.todos-' . $todolistId)) {
                return session('app.todos-' . $todolistId);
            }

            $todos = json_encode(getTodoListTodos($todolistId), JSON_THROW_ON_ERROR);

            if ($todos) {
                session()->put('app.todos-' . $todolistId, $todos);
            }

            return $todos;

        } catch (JsonException) {
            return json_encode([], JSON_THROW_ON_ERROR);
        }
    }
}
