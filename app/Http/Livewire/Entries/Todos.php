<?php

namespace App\Http\Livewire\Entries;

use App\Traits\InteractsWithModal;
use App\Traits\InteractsWithToast;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use JsonException;
use Livewire\Component;

class Todos extends Component
{
    use InteractsWithModal;
    use InteractsWithToast;

    public ?string $selectedProjectId = '';
    public ?string $selectedTodolistId = '';
    public string $name = '';

    // data needed on form
    public ?string $type = '';
    public array $todoLists = [];

    // others
    public bool $loading = false;

    protected array $validationAttributes = [
        'selectedProjectId' => 'Project',
        'selectedTodolistId' => 'Todolist',
    ];

    protected array $messages = [
        'type.required' => 'This field is required.',
    ];

    public function render(): Factory|View|Application
    {
        $projects = user()->projectsAll()
            ->get(['project_id', 'project_name'])
            ->pluck('project_name', 'project_id')
            ->toArray();

        return view('livewire.entries.todos', compact('projects'));
    }

    /**
     * @throws JsonException
     */
    public function updated($propertyName): void
    {
        if ($propertyName === 'type') {
            $this->resetForm();
        }

        /** @noinspection ALL */
        if ($propertyName === 'selectedProjectId') {
            $this->todoLists = [];
            $this->selectedTodolistId = null;

            if ($this->selectedProjectId) {
                $this->todoLists = json_decode($this->fetchTodoLists($this->selectedProjectId), true, 512, JSON_THROW_ON_ERROR);
            }
        }
    }

    public function create(): void
    {
        $this->type = '';

        $this->resetForm();

        $this->openModal();
    }

    public function save(): void
    {
        $this->validate([
            'type' => 'required',
            'name' => 'required|max:75',
            'selectedProjectId' => 'required',
            'selectedTodolistId' => $this->type === 'todo' ? 'required' : '',
        ]);

        set_time_limit(0);

        $this->closeModal();

        $this->loading = true;

        if ($this->type === 'todolist') {

            $action = "projects/$this->selectedProjectId/todo_lists.xml";

            $xmlData = <<<data
                    <todo-list>
                      <name><![CDATA[$this->name]]></name>
                      <private type="boolean">false</private>
                      <tracked type="boolean">true</tracked>
                    </todo-list>
                data;

        } else {

            $action = "todo_lists/$this->selectedTodolistId/todo_items.xml";

            $xmlData = <<<data
                    <todo-item>
                      <content><![CDATA[$this->name]]></content>
                      <notify type="boolean">false</notify>
                    </todo-item>
                data;
        }

        // send to basecamp
        $response = postInfo($action, $xmlData);
        //dd($response);

        // check to see if it was posted successfully to BC
        if ($response && $response['code'] === 201) {
            $this->resetForm();

            $this->loading = false;

            //$this->success('Selected Entries Uploaded Successfully!');
            $this->dispatchBrowserEvent('animated-ok');
        } else {
            $this->loading = false;
            $this->danger('There was an error, please check your data and try again!');
        }
    }

    private function resetForm(): void
    {
        $this->name = '';
        $this->todoLists = [];
        $this->selectedProjectId = '';
        $this->selectedTodolistId = '';

        $this->clearValidation();
    }

    /**
     * @throws JsonException
     */
    public function fetchTodoLists($projectId): bool|string
    {
        try {
            return json_encode(getProjectTodoLists($projectId), JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return json_encode([], JSON_THROW_ON_ERROR);
        }
    }
}
