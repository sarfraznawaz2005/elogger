<div>
    <x-panel title="Time Entries">

        <x-slot name="headerRight">
            <livewire:entries.view-my-hours-log />
        </x-slot>

        <livewire:entries.entry-stats/>

        <div class="flex items-center justify-end mt-8">
            @if(user()->basecamp_api_user_id === '11816315')
                <livewire:entries.replicate/>
            @endif

            <livewire:entries.entry/>
        </div>

        <div class="md:-mt-8 sm:mb-4">
            @include('livewire.entries.tabs')
        </div>
    </x-panel>
</div>
