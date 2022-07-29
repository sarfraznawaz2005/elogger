<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="float-right ml-2">
            <livewire:entry/>
        </div>

        @if(user()->basecamp_api_user_id === '11816315')
            <div class="float-right">
                <livewire:replicate/>
            </div>
        @endif

        <div class="clear-both"></div>

        <livewire:entry-stats/>

        <x-jet-section-border/>

        <livewire:pending-entries-data-table/>

    </div>
</div>
