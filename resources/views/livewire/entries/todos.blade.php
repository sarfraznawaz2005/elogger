<div>

    <?php
    // https://github.com/livewire/livewire/issues/900
    asort($projects);
    asort($todoLists);
    ?>

    <x-status-modal wire:model="loading">
        Please wait...
    </x-status-modal>

    <x-jet-button wire:loading.attr="disabled" wire:click="create" class="bg-yellow-600 hover:bg-yellow-800 ml-4">
        <x-icons.plus/> {{ __('Create Todos') }}
    </x-jet-button>

    <x-jet-dialog-modal wire:model="isModalOpen">

        <x-slot name="title">
            Create Todolists / Todos on Basecamp
        </x-slot>

        <x-slot name="content">
            <x-bc-error/>

            <div x-data="{type:@entangle('type')}">
                <span class="font-semibold">What do you want to create ?</span>

                <div class="flex items-center w-full mt-4">
                    <div class="mr-4 ">
                        <x-jet-label for="todolist" value="{{ __('Todolist') }}"
                                     class="inline-block mt-2 mr-1 font-semibold"/>
                        <x-jet-input type="radio" wire:model="type" name="type" id="todolist" value="todolist"
                                     class="sm:my-0"/>
                    </div>

                    <div class="mr-4">
                        <x-jet-label for="todo" value="{{ __('Todo') }}" class="inline-block mt-2 mr-1 font-semibold"/>
                        <x-jet-input type="radio" wire:model="type" name="type" id="todo" value="todo" class="sm:my-0"/>
                    </div>
                </div>
                <x-jet-input-error for="type" class="mt-2"/>

                <div x-cloak x-show="type">
                    <hr class="divide-x mt-4 mb-6">

                    <div
                        wire:loading wire:target="selectedProjectId"
                        class="fixed top-0 left-0 right-0 bottom-0 w-full z-50 overflow-hidden bg-gray-700 bg-opacity-10 flex flex-col items-center justify-center">
                    </div>

                    <div class="flex items-center">
                        <x-jet-label for="selectedProjectId" value="{{ __('Project') }}" class="w-20 block"/>
                        <select wire:model="selectedProjectId" class="block w-full"
                                wire:loading.attr="disabled"
                                wire:loading.class="disabled:bg-gray-200 disabled:text-gray-800 disabled:border-gray-200 disabled:shadow-none"
                        >
                            <option value="" selected>Choose</option>
                            @foreach($projects as $projectId => $name)
                                <option value="{{ $projectId }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-jet-input-error for="selectedProjectId" class="mt-2 ml-20"/>

                    @if ($selectedProjectId)
                        <div x-cloak x-show="type === 'todo'" class="my-4" wire:loading.remove
                             wire:target="selectedProjectId" wire:key="todos-{{$selectedProjectId}}">
                            <div class="flex items-center">
                                <x-jet-label for="selectedTodolistId" value="{{ __('Todolist') }}" class="w-20 block"/>
                                <select wire:model="selectedTodolistId" class="block w-full">
                                    <option value="" selected>Choose</option>
                                    @foreach($todoLists as $todoListId => $name)
                                        <option value="{{ $todoListId }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <x-jet-input-error for="selectedTodolistId" class="mt-2 ml-20"/>
                        </div>
                    @endif

                    <div class="flex items-center mt-4">
                        <x-jet-label for="name" class="w-20 block" value="Name"/>
                        <x-jet-input id="name" type="text" class="block w-full" wire:model.defer="name"/>
                    </div>
                    <x-jet-input-error for="name" class="mt-2 ml-20"/>
                </div>

            </div>

        </x-slot>

        <x-slot name="footer">
            <x-jet-button
                disabled="{{session()->has('not_connected')}}"
                wire:click="save"
                wire:loading.attr="disabled"
                class="mr-2 bg-blue-700 hover:bg-blue-800">
                <x-icons.ok/> {{ __('Create')  }}
            </x-jet-button>

            <x-jet-button x-on:click="show = false" wire:click="closeModal" wire:loading.attr="disabled">
                {{ __('Cancel')  }}
            </x-jet-button>
        </x-slot>

    </x-jet-dialog-modal>
</div>
