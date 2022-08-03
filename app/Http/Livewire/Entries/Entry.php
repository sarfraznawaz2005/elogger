<?php

namespace App\Http\Livewire\Entries;

use App\Models\Todo;
use App\Services\Data;
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
        'onViewEntry' => 'onViewEntry',
        'onEditEntry' => 'onEditEntry',
        'view' => 'view',
        'edit' => 'edit',
        'onDeleteEntry' => 'delete',
        'onDuplicateEntry' => 'onDuplicateEntry',
        'duplicate' => 'duplicate',
        'onDeleteAllPosted' => 'deleteAllPosted',
        'onDeleteSelected' => 'deleteSelected',
        'onUploadSelected' => 'uploadSelected',
    ];

    public Todo $model;

    // data needed on form
    public array $todoLists = [];
    public array $todos = [];

    public bool $disabled = false;

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
        if (($propertyName === 'model.project_id')) {
            $this->todoLists = [];
            $this->todos = [];
            $this->model->todolist_id = null;
            $this->model->todo_id = null;

            if ($this->model->project_id) {
                $this->todoLists = json_decode($this->todoLists($this->model->project_id), true, 512, JSON_THROW_ON_ERROR);
            }
        }

        if ($propertyName === 'model.todolist_id') {
            $this->todos = [];
            $this->model->todo_id = null;

            if ($this->model->todolist_id) {
                $this->todos = json_decode($this->todos($this->model->todolist_id), true, 512, JSON_THROW_ON_ERROR);
            }
        }
    }

    /** @noinspection ALL */
    public function onViewEntry(Todo $todo): void
    {
        $this->disabled = true;

        $this->emitSelf('view', $todo);
    }

    public function onEditEntry(Todo $todo): void
    {
        $this->disabled = false;

        $this->emitSelf('edit', $todo);
    }

    /** @noinspection ALL */
    public function onDuplicateEntry(Todo $todo): void
    {
        $this->disabled = false;

        $this->emitSelf('duplicate', $todo);
    }

    public function create(): void
    {
        $this->disabled = false;

        $this->model = new Todo();
        $this->model->dated = date('Y-m-d');
        $this->model->time_start = $this->model->time_end = date('H:i');

        $this->resetErrorBag();
        $this->openModal();
    }

    /**
     * @throws JsonException
     */
    public function view(Todo $todo): void
    {
        // because we have disabled fields using $disabled attribute
        $this->edit($todo);
    }

    /**
     * @throws JsonException
     */
    public function edit(Todo $todo): void
    {
        $this->model = $todo;

        $this->todoLists = json_decode($this->todoLists($this->model->project_id), true, 512, JSON_THROW_ON_ERROR);
        $this->todos = json_decode($this->todos($this->model->todolist_id), true, 512, JSON_THROW_ON_ERROR);

        $this->resetErrorBag();
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

        $this->todoLists = json_decode($this->todoLists($this->model->project_id), true, 512, JSON_THROW_ON_ERROR);
        $this->todos = json_decode($this->todos($this->model->todolist_id), true, 512, JSON_THROW_ON_ERROR);

        $this->resetErrorBag();
        $this->openModal();
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

    /** @noinspection ALL */
    public function deleteSelected($ids): void
    {
        if (Todo::whereIn('id', $ids)->delete()) {
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

        /** @noinspection ALL */
        Todo::whereIn('id', $ids)->chunk(50, function ($todos) use (&$posted) {
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
                $responseHeader = postInfo($action, $xmlData);
                //echo $responseHeader;exit;

                // check to see if it was posted successfully to BC
                if (Str::contains($responseHeader, 'Created') || Str::contains($responseHeader, '201')) {
                    // update to do status
                    $todo->status = 'posted';
                    $todo->save();

                    $posted = 'ok';
                } else {
                    $this->success('Todo "' . $todo->description . '" with hours of ' . $hours . ' could not be posted.');
                }

                // so that we do not send post request too fast to BC
                sleep(1);
            }
        });

        if ($posted === 'ok') {
            $monthHours = Data::getUserMonthlyHours();
            session(['month_hours' => $monthHours]);

            $this->emit('refreshLivewireDatatable');
            $this->emit('event-entries-updated');

            $this->success('Selected Entries Uploaded Successfully!');
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
}
