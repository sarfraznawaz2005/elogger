<?php

namespace App\Http\Livewire\Entries;

use App\Models\Todo;
use App\Traits\InteractsWithModal;
use App\Traits\InteractsWithToast;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use JsonException;
use Livewire\Component;

class Entry extends Component
{
    use InteractsWithToast;
    use InteractsWithModal;

    protected $listeners = [
        'onDuplicateEntry',
        'duplicate',
        'onEditEntry',
        'edit',
        'delete',
        'deleteAllPosted',
        'setSelectedItems',
        'deleteSelected',
        'onUploadSelected',
        'uploadSelected',
        'onDeleteFromBasecamp',
        'deleteFromBasecamp',
    ];

    public Todo $model;

    // data needed on form
    public array $todoLists = [];
    public array $todos = [];

    // others
    public string $timeTotal = '0.00';

    // from table
    public array $selectedItems = [];

    public bool $loading = false;
    public string $loadingMessage = 'Loading...';

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
        $this->loadingMessage = 'Loading...';
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
    }

    public function setSelectedItems($values): void
    {
        $this->selectedItems = explode(',', $values);
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
    public function onUploadSelected(): void
    {
        $this->loadingMessage = 'Please wait...';
        $this->loading = true;

        $this->emitSelf('uploadSelected');
    }

    /** @noinspection ALL */
    public function onDeleteFromBasecamp(Todo $todo): void
    {
        $this->loadingMessage = 'Please wait...';
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
        $this->model = $todo->replicate();

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

        $this->emit('refreshLivewireDatatable');
        $this->emit('event-entries-updated');

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

            $this->resetForm();

            $this->emit('refreshLivewireDatatable');
            $this->emit('event-entries-updated');

            $this->success('Entry Deleted Successfully!');
        } else {
            $this->danger('Unable to delete entry!');
        }
    }

    public function deleteSelected(): void
    {
        $ids = $this->selectedItems;

        if (!$ids) {
            $this->danger('There was an error, please try again!');
            return;
        }

        if (Todo::query()->whereIn('id', $ids)->delete()) {

            $this->resetForm();

            $this->emit('refreshLivewireDatatable');
            $this->emit('event-entries-updated');

            $this->success('Selected Entries Deleted Successfully!');
        } else {
            $this->danger('Unable to delete entries!');
        }
    }

    public function uploadSelected(): void
    {
        set_time_limit(0);

        $ids = $this->selectedItems;

        if (!$ids) {
            $this->danger('There was an error, please try again!');
            return;
        }

        $posted = '';

        Todo::query()->whereIn('id', $ids)->chunk(5, function ($todos) use (&$posted) {
            foreach ($todos as $todo) {
                $personId = user()->basecamp_api_user_id;
                $description = trim($todo->description);
                $hours = getBCHoursDiff($todo->dated, $todo->time_start, $todo->time_end);

                $action = 'todo_items/' . $todo->todo_id . '/time_entries.xml';

                $xmlData = <<<data
                        <time-entry>
                          <person-id>$personId</person-id>
                          <date>$todo->dated</date>
                          <hours>$hours</hours>
                          <description><![CDATA[$description]]></description>
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
                    $this->danger('Entry "' . $todo->description . '" with hours of ' . $hours . ' could not be posted.');
                }

                // So that we do not send post request too fast to BC
                // 10 post requests per second is the limit
                sleep(1);
            }
        });

        if ($posted === 'ok') {
            $monthHours = getUserMonthUploadedHours(0, true);
            $uploadedHoursToday = getTotalWorkedHoursForSingleDateCurrentMonth();

            session(['month_hours' => $monthHours]);
            session()->put('uploaded_hours_today', $uploadedHoursToday);

            // for strange livewire reasons so that session can persist
            sleep(1);

            $this->resetForm();

            $this->emit('refreshLivewireDatatable');
            $this->emit('event-entries-updated');

            $this->loading = false;
            //$this->success('Selected Entries Uploaded Successfully!');
            $this->dispatchBrowserEvent('animated-ok');
        } else {
            $this->loading = false;
            $this->danger('Entries Could Not Be Upload!');
        }
    }

    /** @noinspection ALL */
    public function deleteAllPosted(): void
    {
        if (Todo::whereStatus('posted')->delete()) {

            $this->resetForm();

            $this->emit('refreshLivewireDatatable');
            $this->emit('event-entries-updated');

            $this->success('All Posted Entries Deleted Successfully!');
        } else {
            $this->danger('Unable to delete entries!');
        }
    }

    /** @noinspection ALL */
    public function deleteFromBasecamp(Todo $todo): void
    {
        if (!$todo->time_id) {
            $this->loading = false;
            $this->danger('This Entry Cannot Be Deleted From Basecamp!');
            return;
        }

        $responseCode = deleteResource("time_entries/" . $todo->time_id);

        if ($responseCode !== 200) {
            $this->loading = false;
            $this->danger('Entry Could Not Be Deleted From Basecamp!');
            return;
        }

        if ($todo->delete()) {

            $this->resetForm();

            $this->emit('refreshLivewireDatatable');
            $this->emit('event-entries-updated');

            $this->loading = false;
            $this->success('Entry Deleted Successfully!');
        } else {
            $this->loading = false;
            $this->danger('Entry Deleted From Basecamp But Could Not Delete Locally!');
        }
    }

    /**
     *  Needed to avoid 404s after deletions, etc
     */
    private function resetForm(): void
    {
        $this->model = new Todo();
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
