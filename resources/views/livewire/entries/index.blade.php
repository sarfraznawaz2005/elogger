<div>
    <x-panel title="Time Entries">
        <livewire:entries.entry-stats/>

        <div class="flex items-center justify-end mt-8 mb-2">
            @if(user()->basecamp_api_user_id === '11816315')
                <livewire:entries.replicate/>
            @endif

            <livewire:entries.entry/>
        </div>

        @include('livewire.entries.tabs')
    </x-panel>
</div>
