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
use Illuminate\Validation\ValidationException;
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

    public array $todoLists = [];
    public array $todos = [];

    public ?array $item = null;

    // used for edits
    public int $itemId = 0;

    public bool $disabled = false;
    public bool $loading = false;

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

        $this->resetForm();

        $this->item['dated'] = date('Y-m-d');
        $this->item['time_start'] = date('H:i');
        $this->item['time_end'] = date('H:i');

        $this->openModal();
    }

    public function save(): void
    {
        $isCreate = $this->itemId === 0;

        $data = $this->validate();
        $data = $data['item'];

        $data['user_id'] = user()->id;

        // make sure end time is greater than start time
        $diff = getBCHoursDiff($data['dated'], $data['time_start'], $data['time_end'], true);

        if ($diff < 0) {
            $this->danger('Start Time cannot be greater than End Time.');
            return;
        }

        if ($diff === '0' || $diff === '0.00') {
            $this->danger('Start Time and End Time cannot be same.');
            return;
        }

        /** @noinspection ALL */
        $todo = Todo::updateOrCreate(['id' => $this->itemId], $data);

        if (!$todo->save()) {
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

    /** @noinspection ALL */
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

    /** @noinspection ALL */
    public function onDuplicateEntry($id): void
    {
        $this->loading = true;
        $this->disabled = false;

        $this->emitSelf('duplicate', $id);
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
        /** @noinspection ALL */
        if (Todo::whereIn('id', $ids)->delete()) {
            $this->emit('refreshLivewireDatatable');
            $this->emit('event-entries-updated');

            $this->success('Selected Entries Deleted Successfully!');
        } else {
            $this->danger('Unable to delete entries!');
        }
    }

    /**
     * @throws JsonException
     */
    public function duplicate($id): void
    {
        // reset so that create form can be used again
        $this->itemId = 0;

        // clear validation messages
        $this->resetErrorBag();

        /** @noinspection ALL */
        $todo = Todo::findOrFail($id);

        $this->item['project_id'] = $todo->project_id;
        $this->item['todolist_id'] = $todo->todolist_id;
        $this->item['todo_id'] = $todo->todo_id;

        $this->item['description'] = '';
        $this->item['dated'] = date('Y-m-d');
        $this->item['time_start'] = date('H:i');
        $this->item['time_end'] = date('H:i');

        $this->todoLists = json_decode($this->todoLists($todo->project_id), true, 512, JSON_THROW_ON_ERROR);
        $this->todos = json_decode($this->todos($todo->todolist_id), true, 512, JSON_THROW_ON_ERROR);

        $this->loading = false;

        $this->openModal();
    }

    public function resetForm(): void
    {
        // clear validation messages
        $this->resetErrorBag();

        // reset so that create form can be used again
        $this->itemId = 0;

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

            $this->dispatchBrowserEvent('hide-waiting-message');

            $this->emit('refreshLivewireDatatable');
            $this->emit('event-entries-updated');

            $this->success('Selected Entries Uploaded Successfully!');
        }
    }
}
