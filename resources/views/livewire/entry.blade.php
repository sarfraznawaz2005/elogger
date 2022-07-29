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

            <div class="my-4">
                <x-jet-label for="item.project_id" value="{{ __('Project') }}"/>
                <select wire:model="item.project_id" class="mt-1 block w-full">
                    <option value="" selected>Choose</option>
                    @foreach($projects as $projectId => $name)
                        <option value="{{ $projectId }}">{{ $name }}</option>
                    @endforeach
                </select>
                <x-jet-input-error for="item.project_id" class="mt-2"/>
            </div>

            <x-loading wire:loading wire:target="item.project_id"></x-loading>

            @if (isset($item['project_id']) && $item['project_id'])
                <div wire:loading.remove wire:target="item.project_id" class="my-4">
                    <x-jet-label for="item.todolist_id" value="{{ __('Todolist') }}"/>
                    <select wire:model="item.todolist_id" class="mt-1 block w-full">
                        <option value="" selected>Choose</option>
                        @foreach($todoLists as $todoListId => $name)
                            <option value="{{ $todoListId }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-jet-input-error for="item.todolist_id" class="mt-2"/>
                </div>
            @endif

            <x-loading wire:loading wire:target="item.todolist_id"></x-loading>

            @if (isset($item['todolist_id']) && $item['todolist_id'])
                <div wire:loading.remove wire:target="item.project_id, item.todolist_id" class="my-4">
                    <x-jet-label for="item.todo_id" value="{{ __('Todo') }}"/>
                    <select wire:model="item.todo_id" class="mt-1 block w-full">
                        <option value="" selected>Choose</option>
                        @foreach($todos as $todoId => $name)
                            <option value="{{ $todoId }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    <x-jet-input-error for="item.todo_id" class="mt-2"/>
                </div>
            @endif

            <hr class="my-8">

            <div class="grid grid-cols-3 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-6 w-full mb-4">
                <div>
                    <x-jet-label for="item.dated" value="{{ __('Date') }}" />
                    <x-jet-input id="item.dated" type="date" class="mt-1 block w-full" wire:model="item.dated" />
                    <x-jet-input-error for="item.dated" class="mt-2" />
                </div>

                <div>
                    <x-jet-label for="item.time_start" value="{{ __('Time Start') }}" />
                    <x-jet-input id="item.time_start" type="time" class="mt-1 block w-full" wire:model="item.time_start" />
                    <x-jet-input-error for="item.time_start" class="mt-2" />
                </div>

                <div>
                    <x-jet-label for="item.time_end" value="{{ __('Time End') }}" />
                    <x-jet-input id="item.time_end" type="time" class="mt-1 block w-full" wire:model="item.time_end" />
                    <x-jet-input-error for="item.time_end" class="mt-2" />
                </div>
            </div>

            <x-jet-label for="item.description" value="{{ __('Description') }}" />
            <x-jet-input id="item.description" type="text" class="mt-1 block w-full" wire:model="item.description" />
            <x-jet-input-error for="item.description" class="mt-2" />

        </x-slot>

        <x-slot name="footer">
            <x-jet-button
                wire:click="create"
                wire:loading.attr="disabled"
                class="mr-2 bg-blue-700 hover:bg-blue-800 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                {{ __('Add Entry')  }}
            </x-jet-button>

            <x-jet-button wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Cancel')  }}
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
