
<div class="overflow-x-auto relative sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-2 text-center">Name</th>
                <th scope="col" class="py-3 px-2 text-center">Exchange</th>
                <th scope="col" class="py-3 px-2 text-center">Testnet</th>
                <th scope="col" class="py-3 px-2 text-center">Wallet Exposure</th>
                <th scope="col" class="py-3 px-2 text-center">TWE</th>
                <th scope="col" class="py-3 px-2 text-center">Risk mode</th>
                <th scope="col" class="py-3 px-2 text-center">Bots</th>
                <th scope="col" class="py-3 px-2 text-center">Pos.</th>
                <th scope="col" class="py-3 px-2 text-center">Trades</th>
                {{-- <th scope="col" class="py-3 px-2 text-center">Created</th> --}}
                <th scope="col" class="py-3 px-2 text-center"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                @php
                    $testnet_color = $record->is_testnet ? 'green' : 'red';
                    $lwe = $record->long_wallet_exposure;
                    $swe = $record->short_wallet_exposure;
                    $total_we = $swe + $lwe;
                @endphp
                <tr class="bg-white dark:bg-gray-900{{ $loop->last ? '' : ' border-b dark:border-gray-400'}} hover:bg-gray-100 hover:dark:bg-teal-500 hover:dark:text-white">

                    <td class="py-2 px-2 font-bold underline hover:no-underline">
                        <a href="{{ route('exchanges.edit', $record) }}">
                            {{ $record->name }}
                        </a>
                    </td>
                    <td class="py-2 px-2">
                        {{ \Arr::get($exchanges, $record->exchange->value) }}
                    </td>
                    <td class="py-2 px-2 text-center">
                        <div class="h-2.5 w-2.5 rounded-full bg-{{$testnet_color}}-500 ml-4 mt-0.5"></div>
                    </td>
                    <td class="py-2 px-2 text-center">
                        Long: {{ number_format($lwe, 2) }}&nbsp;&nbsp;Short: {{ number_format($swe, 2) }}
                    </td>
                    <td class="py-2 px-2 text-right font-bold text-{{ $total_we > $record->max_exposure ? 'red-600' : 'green-500'}}">
                        {{ number_format($total_we, 2) }}
                    </td>
                    <td class="py-2 px-2 text-center">
                        {{ \Arr::get($risk_modes, $record->risk_mode) }}
                    </td>
                    <td class="py-2 px-2 text-center">
                        {{ $record->running_bots->count() }}/{{ $record->bots_count }}
                    </td>
                    <td class="py-2 px-2 text-center">
                        {{ $record->positions_count }}
                    </td>
                    <td class="py-2 px-2 text-center">
                        {{ $record->trades_count }}
                    </td>
                    {{-- <td class="py-2 px-2 text-center">
                        {{ $record->created_at->format('d-m-Y') }}
                    </td> --}}
                    <td class="py-2 px-2 text-right">
                        <div class="inline-flex rounded-md shadow-sm" role="group">
                          <a href="{{route('exchanges.positions', $record)}}" title="Open positions" type="button" class="inline-flex items-center px-2 py-2 text-xs font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                          </a>
                          <a href="{{ route('exchanges.trades', $record) }}" title="Trade records" class="inline-flex items-center px-2 py-2 text-xs font-medium text-gray-900 bg-white border-t border-b border-r border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                          </a>
                          <button href="{{ route('exchanges.edit', $record) }}" title="Edit" class="inline-flex items-center px-2 py-2 text-xs font-medium text-gray-900 bg-white border-t border-b border-gray-200 rhover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                          </button>
                          <button x-data="" wire:click="deleteId({{ $record->id }})" title="Delete" x-on:click.prevent="$dispatch('open-modal', 'confirm-exchange-deletion')" type="button" class="inline-flex items-center px-2 py-2 text-xs font-medium text-gray-900 bg-red-600 hover:bg-red-500 active:bg-red-700 border border-gray-200 rounded-r-md hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:focus:ring-blue-500 dark:focus:text-white">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                          </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="text-center bg-white dark:bg-gray-900">
                    <td colspan="11" class="py-4 px-2 italic">No hay información</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


<x-modal name="confirm-exchange-deletion" focusable>
    <div class="p-4">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Are you sure your want to delete the exchange?</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('This action can NOT be undone.') }}
        </p>
        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-danger-button class="ml-3" wire:click.prevent="destroy()" x-on:click="$dispatch('close')">
                {{ __('Delete Exchange') }}
            </x-danger-button>
        </div>
    </div>
</x-modal>
