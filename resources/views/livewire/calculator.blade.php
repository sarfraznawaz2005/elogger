<div>
    <x-panel title="Calculator">

        <div x-data="{loading : false}" x-init="Livewire.hook('message.processed', () => { loading = false })">

            <div
                style="display: none;"
                x-show="loading"
                class="fixed top-0 left-0 right-0 bottom-0 w-full z-50 overflow-hidden bg-gray-700 bg-opacity-10 flex flex-col items-center justify-center">
                    <svg class="inline w-8 h-8 text-gray-600 animate-spin mr-2" viewBox="0 0 100 101" fill="blue" xmlns="http://www.w3.org/2000/svg">
                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="currentColor"/>
                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentFill"/>
                    </svg>
            </div>

            <table class="table-auto overflow-x-auto text-sm text-center text-gray-700 mx-auto">
                <thead>

                <tr>
                    <th>
                        <div class="overflow-x-auto mx-auto mb-4">
                            <div class="overflow-x-auto flex items-center">
                                <x-jet-label for="allowedLeaves" value="{{ __('Allowed Leaves') }}" class="mr-2 font-bold"/>
                                <x-jet-input id="allowedLeaves" type="text" class="w-16 text-center" wire:model="allowedLeaves"/>
                            </div>
                        </div>
                    </th>
                    <th colspan="4">
                        <div class="overflow-x-auto mx-auto mb-4">
                            <div class="overflow-x-auto flex items-center">
                                <x-jet-label for="absents" value="{{ __('Total Absents') }}" class="mr-2 font-bold"/>
                                <x-jet-input id="absents" type="text" class="w-16 text-center" wire:model="absents"/>
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
                    <th scope="col" class="py-4 px-6">
                        Difference
                    </th>
                </tr>
                </thead>
                <tbody>

                <tr class="bg-white">
                    <td colspan="99">&nbsp;</td>
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
                            <select wire:model="items.{{$index}}.month" class="w-full"
                                    x-on:change="loading = $event.target.value"
                                    wire:loading.attr="disabled"
                                    wire:loading.class="disabled:bg-gray-200 disabled:text-gray-800 disabled:border-gray-200 disabled:shadow-none"
                            >
                                <option value="" selected>Choose</option>
                                @foreach(json_decode($months, false, 512, JSON_THROW_ON_ERROR) as $monthValue => $monthName)
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
                            <x-jet-input type="text" disabled
                                         class="w-16 text-center disabled:bg-gray-200 disabled:text-gray-800 disabled:border-gray-200 disabled:shadow-none"
                                         wire:model="items.{{$index}}.logged_hours"/>
                        </td>
                        <td class="py-1 px-6">
                            <x-jet-input disabled type="text" class="w-16 text-center {{$items[$index]['diff'] < 0 ? 'bg-red-200' : 'bg-green-200'}}" wire:model="items.{{$index}}.diff"/>
                        </td>
                    </tr>
                @endforeach

                <tr class="bg-white">
                    <td colspan="99">&nbsp;</td>
                </tr>

                <tr class="bg-white">
                    <td colspan="99"><hr></td>
                </tr>

                <tr class="bg-white border-b">
                    <td class="font-bold text-md py-4 uppercase py-2 px-6 text-left">Total</td>
                    <td>&nbsp;</td>
                    <td>
                        <x-jet-input disabled type="text" class="w-20 font-semibold text-center bg-gray-200 text-sm" value="{{$totalRequired}}"/>
                    </td>
                    <td>
                        <x-jet-input disabled type="text" class="w-20 font-semibold text-center bg-gray-200 text-sm" value="{{$totalLogged}}"/>
                    </td>
                    <td>
                        <x-jet-input disabled type="text" class="w-20 font-semibold text-center text-sm {{$totalDiff < 0 ? 'bg-red-200' : 'bg-gray-200'}}" value="{{$totalDiff}}"/>
                    </td>
                </tr>

                <tr class="bg-white border-b">
                    <td class="font-bold text-md py-4 uppercase py-2 px-6 text-left">Final Hours</td>
                    <td colspan="3">&nbsp;</td>
                    <td>
                        <x-jet-input disabled type="text" class="w-20 font-bold text-center text-md {{$finalHours && $finalHours < 0 ? 'bg-red-200' : 'bg-green-200'}}" value="{{$finalHours}}"/>
                    </td>
                </tr>

                <tr class="bg-white">
                    <td class="font-bold text-md py-4 uppercase py-2 px-6 text-left">Hours Average</td>
                    <td colspan="3">&nbsp;</td>
                    <td>
                        <x-jet-input disabled type="text" class="w-20 font-bold text-center text-md {{$hoursAvg !== '0.00' && $hoursAvg < 8 ? 'bg-red-200' : 'bg-green-200'}}" value="{{$hoursAvg}}"/>
                    </td>
                </tr>

                </tbody>
            </table>

            <div class="flex justify-center pt-4">
                <x-jet-button wire:loading.attr="disabled" wire:click="calculate"
                              class="bg-green-700 ml-4 hover:bg-green-800">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    {{ __('Calculate') }}
                </x-jet-button>
            </div>
        </div>

    </x-panel>
</div>
