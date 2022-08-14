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

            <x-bc-error />

            <div
                wire:loading wire:target="model.project_id, model.todolist_id"
                class="fixed top-0 left-0 right-0 bottom-0 w-full z-50 overflow-hidden bg-gray-700 bg-opacity-10 flex flex-col items-center justify-center">
            </div>

            <div class="my-4">
                <div class="flex items-center mt-1">
                    <x-jet-label for="model.project_id" value="{{ __('Project') }}" class="w-20 block"/>
                    <select wire:model="model.project_id" class="ml-2 block w-full"
                            wire:loading.attr="disabled"
                            wire:loading.class="disabled:bg-gray-200 disabled:text-gray-800 disabled:border-gray-200 disabled:shadow-none"
                    >
                        <option value="" selected>Choose</option>
                        @foreach($projects as $projectId => $name)
                            <option value="{{ $projectId }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <x-jet-input-error for="model.project_id" class="mt-2 ml-20"/>
            </div>

            @if (isset($model) && $model->project_id)
                <div class="my-4" wire:loading.remove wire:target="model.project_id" wire:key="{{$model->project_id}}">
                    <div class="flex items-center mt-1">
                        <x-jet-label for="model.todolist_id" value="{{ __('Todolist') }}" class="w-20 block"/>
                        <select wire:model="model.todolist_id" class="ml-2 block w-full"
                                wire:loading.attr="disabled"
                                wire:loading.class="disabled:bg-gray-200 disabled:text-gray-800 disabled:border-gray-200 disabled:shadow-none"
                        >
                            <option value="" selected>Choose</option>
                            @foreach($todoLists as $todoListId => $name)
                                <option value="{{ $todoListId }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-jet-input-error for="model.todolist_id" class="mt-2 ml-20"/>
                </div>
            @endif

            @if (isset($model) && $model->todolist_id)
                <div class="my-4" wire:loading.remove wire:target="model.project_id, model.todolist_id" wire:key="{{$model->todolist_id}}">
                    <div class="flex items-center mt-1">
                        <x-jet-label for="model.todo_id" value="{{ __('Todo') }}" class="w-20 block"/>
                        <select wire:model="model.todo_id" class="ml-2 block w-full">
                            <option value="" selected>Choose</option>
                            @foreach($todos as $todoId => $name)
                                <option value="{{ $todoId }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-jet-input-error for="model.todo_id" class="mt-2 ml-20"/>
                </div>
            @endif

            <hr class="divide-x mt-8 mb-6">

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
                                     class="text-center bg-blue-100 text-md font-semibold"
                                     wire:model="timeTotal" disabled/>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <x-jet-label for="model.description" value="{{ __('Description') }}"/>
                <x-jet-input wire:keydown.enter="save" id="model.description" type="text" class="mt-1 block w-full"
                             wire:model="model.description"/>

                <x-jet-input-error for="model.description" class="mt-2"/>
            </div>

        </x-slot>

        <x-slot name="footer">

            <x-jet-button
                disabled="{{session()->has('not_connected')}}"
                wire:click="save"
                wire:loading.attr="disabled"
                class="mr-2 bg-blue-700 hover:bg-blue-800">
                {{ __('Save Entry')  }}
            </x-jet-button>

            <x-jet-button x-on:click="show = false" wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Close')  }}
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
