<div>

    <?php
    // https://github.com/livewire/livewire/issues/900
    asort($todoLists);
    asort($todos);
    ?>

    <x-status-modal wire:model="loading">
        {{$loadingMessage}}
    </x-status-modal>

    <x-jet-button
        wire:loading.attr="disabled"
        wire:click="create"
        class="bg-green-700 ml-4 hover:bg-green-800">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"
             stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
        </svg>

        {{ __('Add Entry') }}
    </x-jet-button>

    <x-jet-dialog-modal wire:model="isModalOpen">

        <x-slot name="title">
            {{$modalTitle}}
        </x-slot>

        <x-slot name="content">

            <div wire:key="dropdown_div">

                <div class="my-4">
                    <x-jet-label for="model.project_id" value="{{ __('Project') }}"/>
                    <select wire:model="model.project_id" class="mt-1 block w-full"
                            wire:loading.attr="disabled"
                            wire:loading.class="disabled:bg-gray-200 disabled:text-gray-800 disabled:border-gray-200 disabled:shadow-none"
                    >
                        <option value="" selected>Choose</option>
                        @foreach($projects as $projectId => $name)
                            <option value="{{ $projectId }}">{{ $name }}</option>
                        @endforeach
                    </select>

                    <x-jet-input-error for="model.project_id" class="mt-2"/>
                </div>

                @if (isset($model) && $model->project_id)
                    <div class="my-4" wire:loading.remove wire:target="model.project_id" wire:key="{{$model->project_id}}">
                        <x-jet-label for="model.todolist_id" value="{{ __('Todolist') }}"/>
                        <select wire:model="model.todolist_id" class="mt-1 block w-full"
                                wire:loading.attr="disabled"
                                wire:loading.class="disabled:bg-gray-200 disabled:text-gray-800 disabled:border-gray-200 disabled:shadow-none"
                        >
                            <option value="" selected>Choose</option>
                            @foreach($todoLists as $todoListId => $name)
                                <option value="{{ $todoListId }}">{{ $name }}</option>
                            @endforeach
                        </select>

                        <x-jet-input-error for="model.todolist_id" class="mt-2"/>
                    </div>
                @endif

                @if (isset($model) && $model->todolist_id)
                    <div class="my-4" wire:loading.remove wire:target="model.project_id, model.todolist_id" wire:key="{{$model->todolist_id}}">
                        <x-jet-label for="model.todo_id" value="{{ __('Todo') }}"/>
                        <select wire:model="model.todo_id" class="mt-1 block w-full">
                            <option value="" selected>Choose</option>
                            @foreach($todos as $todoId => $name)
                                <option value="{{ $todoId }}">{{ $name }}</option>
                            @endforeach
                        </select>

                        <x-jet-input-error for="model.todo_id" class="mt-2"/>
                    </div>
                @endif

                <div wire:loading.block wire:target="model.project_id, model.todolist_id">
                    <svg class="inline w-8 h-8 text-gray-600 animate-spin" viewBox="0 0 100 101" fill="blue" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
                </div>
            </div>

            <hr class="my-8">

            <div class="inline-flex items-center justify-between w-full">
                <div>
                    <x-jet-label for="model.dated" value="{{ __('Date') }}"/>
                    <x-jet-input id="model.dated" type="date" class="block w-48" wire:model="model.dated"/>

                    <x-jet-input-error for="model.dated" class="mt-2"/>
                </div>

                <div class="inline-flex items-center justify-end w-full">
                    <div class="mr-4">
                        <x-jet-label for="model.time_start" value="{{ __('Start Time') }}"/>
                        <x-jet-input id="model.time_start" type="time" class="block w-full"
                                     wire:model="model.time_start"/>

                        <x-jet-input-error for="model.time_start" class="mt-2"/>
                    </div>

                    <div class="mr-4">
                        <x-jet-label for="model.time_end" value="{{ __('End Time') }}"/>
                        <x-jet-input id="model.time_end" type="time" class="block w-full" wire:model="model.time_end"/>

                        <x-jet-input-error for="model.time_end" class="mt-2"/>
                    </div>

                    <div>
                        <x-jet-label for="timeTotal" value="{{ __('Total') }}"/>
                        <x-jet-input id="timeTotal" type="text" style="width:70px;"
                                     class="text-center bg-green-100 text-green-800 text-md font-semibold"
                                     wire:model="timeTotal" disabled/>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <x-jet-label for="model.description" value="{{ __('Description') }}"/>
                <x-jet-input id="model.description" type="text" class="mt-1 block w-full"
                             wire:model="model.description"/>

                <x-jet-input-error for="model.description" class="mt-2"/>
            </div>

        </x-slot>

        <x-slot name="footer">

            <x-jet-button
                wire:click="save"
                wire:loading.attr="disabled"
                class="mr-2 bg-blue-700 hover:bg-blue-800">
                {{ __('Save Entry')  }}
            </x-jet-button>

            <x-jet-button wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Close')  }}
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
