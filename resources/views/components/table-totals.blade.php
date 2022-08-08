<div class="flex items-center justify-end mb-2">
    @if($isPendingTable)
        <x-label-segmented
            class="font-semibold mr-0"
            color="blue"
            label="Pending Total"
            value="{{number_format(user()->pendingTodosHours(), 2)}}"/>
    @endif

    @if(!$isPendingTable)
        <x-label-segmented
            class="font-semibold mr-0"
            color="blue"
            label="Uploaded Total"
            value="{{number_format(user()->postedTodosHours(), 2)}}"/>
    @endif
</div>
