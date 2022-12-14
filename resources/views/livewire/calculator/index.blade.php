<div>
    <x-panel title="Calculator" class="m-0 p-0">

        <div x-data="{loading : false}" x-init="Livewire.hook('message.processed', () => { loading = false })">

            <div
                x-cloak
                x-show="loading"
                class="fixed top-0 left-0 right-0 bottom-0 w-full z-50 overflow-hidden bg-gray-700 bg-opacity-10 flex flex-col items-center justify-center">
                <x-icons.spinner />
            </div>

            <div class="bg-gray-200 p-4">
                <table class="table-auto overflow-x-auto text-sm text-center text-gray-800 mx-auto">
                    <thead>

                    <tr>
                        <th>
                            <div class="overflow-x-auto mx-auto mb-4">
                                <div class="overflow-x-auto flex items-center">
                                    <x-jet-label for="allowedLeaves" value="{{ __('Allowed Leaves Hours') }}" class="mr-2 font-bold"/>
                                    <x-jet-input id="allowedLeaves" type="text" class="w-16 text-center rounded h-10" wire:model.debounce.500ms="allowedLeaves"/>
                                </div>
                            </div>
                        </th>
                        <th>
                            <div class="overflow-x-auto mx-auto mb-4 ml-2">
                                <div class="overflow-x-auto flex items-center">
                                    <x-jet-label for="absents" value="{{ __('Total Absents') }}" class="mr-2 font-bold"/>
                                    <x-jet-input id="absents" type="text" class="w-16 text-center rounded h-10" wire:model.debounce.500ms="absents"/>
                                </div>
                            </div>
                        </th>
                        <th colspan="3">
                            <div class="overflow-x-auto mx-auto mb-4">
                                <div class="overflow-x-auto flex items-center justify-end">
                                    <x-jet-label for="year" value="{{ __('Year') }}" class="mr-2 font-bold"/>
                                    <select wire:model.debounce.500ms="year" id="year" class="w-32 rounded focus:border focus:ring-0 border-gray-300 focus:border-blue-600 focus:ring focus:ring-blue-200 shadow-sm h-10">
                                        @foreach($years as $year)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </th>
                    </tr>

                    <tr class="text-xs text-gray-600 uppercase bg-gray-100">
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
                        <th scope="col" class="py-4 px-4">
                            Difference
                        </th>
                    </tr>
                    </thead>

                    <tbody>

                    <tr class="bg-white">
                        <td colspan="99"><p class="h-2"></p></td>
                    </tr>

                    <tr class="bg-white">
                        <td colspan="99">
                            @if ($errors->any())
                                <div class="mb-4">
                                    @foreach ($errors->all() as $error)
                                        <span class="font-bold text-red-600">{{ $error }}</span><br>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                    </tr>

                    @foreach($items as $index => $item)
                        <tr class="bg-white text-sm text-gray-900 bg-white" wire:key="row-{{$index}}">
                            <td class="py-1 px-6">
                                <select wire:model="items.{{$index}}.month" class="w-full rounded focus:border focus:ring-0 border-gray-300 focus:border-blue-600 focus:ring focus:ring-blue-200 shadow-sm h-10"
                                        x-on:change="loading = $event.target.value"
                                        wire:loading.attr="disabled"
                                        wire:loading.class="bg-gray-300 disabled:text-gray-800 disabled:border-gray-200 disabled:shadow-none"
                                >
                                    <option value="" selected>Choose</option>
                                    @foreach(json_decode($months, false, 512, JSON_THROW_ON_ERROR) as $monthValue => $monthName)
                                        <option value="{{ $monthValue }}">{{ $monthName }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="py-1 px-6">
                                <x-jet-input type="text" class="w-16 text-center rounded h-10" wire:model.debounce.300ms="items.{{$index}}.working_days"/>
                            </td>
                            <td class="py-1 px-6">
                                <x-jet-input type="text" class="w-16 text-center rounded h-10" wire:model.debounce.300ms="items.{{$index}}.required_hours"/>
                            </td>
                            <td class="py-1 px-6">
                                <x-jet-input type="text" disabled
                                             class="w-16 text-center rounded bg-gray-200 disabled:text-gray-800 border-0 disabled:border-gray-200 disabled:shadow-none h-10"
                                             wire:model="items.{{$index}}.logged_hours"/>
                            </td>
                            <td class="py-1 px-6">
                                <x-jet-input disabled type="text" class="w-16 text-center rounded border-0 h-10 {{$items[$index]['diff'] < 0 ? 'bg-red-200' : 'bg-green-200'}}" wire:model="items.{{$index}}.diff"/>
                            </td>
                        </tr>
                    @endforeach

                    <tr class="bg-white">
                        <td colspan="99"><p class="h-2"></p></td>
                    </tr>

                    <tr class="bg-white">
                        <td colspan="99"><hr></td>
                    </tr>

                    <tr class="bg-white border-b">
                        <td class="font-bold text-md py-4 uppercase py-2 px-6 text-left">Total</td>
                        <td>&nbsp;</td>
                        <td>
                            <x-jet-input disabled type="text" class="w-16 font-semibold h-10 text-center rounded border-0 bg-gray-200" value="{{$totalRequired}}"/>
                        </td>
                        <td>
                            <x-jet-input disabled type="text" class="w-16 font-semibold h-10 text-center rounded border-0 bg-gray-200" value="{{$totalLogged}}"/>
                        </td>
                        <td>
                            <x-jet-input disabled type="text" class="w-16 font-semibold h-10 text-center rounded border-0 {{$totalDiff < 0 ? 'bg-red-200' : 'bg-green-200'}}" value="{{$totalDiff}}"/>
                        </td>
                    </tr>

                    <tr class="bg-gray-200">
                        <td colspan="99"><p class="h-1"></p></td>
                    </tr>

                    <tr class="bg-white border-b">
                        <td class="font-bold text-md uppercase py-4 px-6 text-left">Final Hours</td>
                        <td colspan="3">&nbsp;</td>
                        <td>
                            <x-jet-input disabled type="text" class="w-16 font-bold h-10 text-center rounded border-0 text-md {{$finalHours && $finalHours < 0 ? 'bg-red-200' : 'bg-green-200'}}" value="{{$finalHours}}"/>
                        </td>
                    </tr>

                    <tr class="bg-white">
                        <td class="font-bold text-md uppercase py-4 px-6 text-left">Hours Average</td>
                        <td colspan="3">&nbsp;</td>
                        <td>
                            <x-jet-input disabled type="text" class="w-16 font-bold h-10 text-center rounded border-0 text-md {{$hoursAvg !== '0.00' && $hoursAvg < 8 ? 'bg-red-200' : 'bg-green-200'}}" value="{{$hoursAvg}}"/>
                        </td>
                    </tr>

                    </tbody>
                </table>

                <div class="flex justify-center pt-6">
                    <x-jet-button wire:click="save" wire:loading.attr="disabled" class="bg-green-600 hover:bg-green-700">
                        <x-icons.ok/> {{ __('Save Calculations') }}
                    </x-jet-button>
                </div>

            </div>

        </div>

    </x-panel>
</div>
