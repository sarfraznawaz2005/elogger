<div>
    <x-panel title="Calculator">

        <div class="overflow-x-auto mx-auto flex justify-center">
            <table class="table-auto text-sm text-center text-gray-700">
                <thead class="text-xs text-gray-600 uppercase bg-gray-100">
                <tr>
                    <th scope="col" class="py-4 px-6">
                        Month
                    </th>
                    <th scope="col" class="py-4 px-6">
                        Working Days
                    </th>
                    <th scope="col" class="py-4 px-6">
                        Required Hours
                    </th>
                    <th scope="col" class="py-4 px-6">
                        Logged Hours
                    </th>
                    <th scope="col" class="py-4 px-6">
                        Difference
                    </th>
                </tr>
                </thead>
                <tbody>

                <tr class="bg-white"><td colspan="99">&nbsp;</td></tr>

                @foreach($items as $index => $item)
                    <tr class="bg-white text-sm text-gray-900 bg-white" wire:key="row-{{$index}}">
                        <td class="py-1 px-6">
                            <select wire:model="items.{{$index}}.month" class="w-full">
                                <option value="" selected>Choose</option>
                                @foreach(json_decode($months) as $monthValue => $monthName)
                                    <option value="{{ $monthValue }}">{{ $monthName }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td class="py-1 px-6">
                            <x-jet-input type="text" class="w-16 text-center" wire:model="items.{{$index}}.working_days"/>
                        </td>
                        <td class="py-1 px-6">
                            <x-jet-input type="text" class="w-16 text-center" wire:model="items.{{$index}}.required_hours"/>
                        </td>
                        <td class="py-1 px-6">
                            <x-jet-input type="text" class="w-16 text-center" wire:model="items.{{$index}}.logged_hours"/>
                        </td>
                        <td class="py-1 px-6">
                            <x-jet-input disabled type="text" class="w-16 text-center" wire:model="items.{{$index}}.diff"/>
                        </td>
                    </tr>
                @endforeach

                <tr class="bg-white"><td colspan="99">&nbsp;</td></tr>

                </tbody>
            </table>

        </div>

        <div class="flex justify-center pt-4">
            <x-jet-button wire:loading.attr="disabled" wire:click="calculate" class="bg-green-700 ml-4 hover:bg-green-800">
                {{ __('Calculate') }}
            </x-jet-button>
        </div>

    </x-panel>
</div>
