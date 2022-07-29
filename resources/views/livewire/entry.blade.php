<div>

    <x-jet-button wire:loading.attr="disabled" wire:click="openModal"
                  class="bg-green-700 hover:bg-green-800 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>

        {{ __('Add New Entry') }}
    </x-jet-button>

    <x-jet-dialog-modal wire:model="isModalOpen">

        <x-slot name="title">
            Add New Entry
        </x-slot>

        <x-slot name="content">

            <div class="col-span-6 sm:col-span-4 my-4">
                <x-jet-label for="project_id" value="{{ __('Project') }}"/>
                <select wire:model="project_id" class="mt-1 block w-full">
                    <option value="" selected>Choose</option>
                    @foreach($projects as $projectId => $name)
                        <option value="{{ $projectId }}">{{ $name }}</option>
                    @endforeach
                </select>
                <x-jet-input-error for="item.project_id" class="mt-2"/>
            </div>

            <x-loading wire:loading wire:target="project_id"></x-loading>

            @if ($project_id)
                <div wire:loading.remove wire:target="project_id" class="col-span-6 sm:col-span-4 my-4">
                    <x-jet-label for="todolist_id" value="{{ __('Todolist') }}"/>
                    <select wire:model="todolist_id" class="mt-1 block w-full">
                        <option value="" selected>Choose</option>
                        @foreach($todoLists as $todoListId => $name)
                            <option value="{{ $todoListId }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-jet-input-error for="item.todolist_id" class="mt-2"/>
                </div>
            @endif

            <x-loading wire:loading wire:target="todolist_id"></x-loading>

            @if ($todolist_id)
                <div wire:loading.remove wire:target="project_id, todolist_id" class="col-span-6 sm:col-span-4 my-4">
                    <x-jet-label for="todo_id" value="{{ __('Todo') }}"/>
                    <select wire:model="todo_id" class="mt-1 block w-full">
                        <option value="" selected>Choose</option>
                        @foreach($todos as $todoId => $name)
                            <option value="{{ $todoId }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-jet-input-error for="item.todo_id" class="mt-2"/>
                </div>
            @endif

        </x-slot>

        <x-slot name="footer">
            <x-jet-button wire:click="create" wire:loading.attr="disabled"
                          class="mr-2 bg-blue-700 hover:bg-blue-800 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                {{ __('Add Entry')  }}
            </x-jet-button>

            <x-jet-button wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Cancel')  }}
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
