<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-gray-200 py-8 rounded-lg">

        <livewire:entries.entry-stats/>

        <div class="flex items-center justify-end mt-8 mb-2">
            @if(user()->basecamp_api_user_id === '11816315')
                <livewire:entries.replicate/>
            @endif

            <livewire:entries.entry/>
        </div>

        @include('livewire.entries.tabs')

    </div>
</div>
