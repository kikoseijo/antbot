
<div class="overflow-x-auto relative sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-4 text-center">Name</th>
                <th scope="col" class="py-3 px-4 text-center">Exchange</th>
                <th scope="col" class="py-3 px-4 text-center">Testnet</th>
                <th scope="col" class="py-3 px-4 text-center">Wallet Exposure</th>
                <th scope="col" class="py-3 px-4 text-center">TWE</th>
                <th scope="col" class="py-3 px-4 text-center">Risk mode</th>
                <th scope="col" class="py-3 px-4 text-center">Bots</th>
                <th scope="col" class="py-3 px-4 text-center">Pos.</th>
                <th scope="col" class="py-3 px-4 text-center">Trades</th>
                {{-- <th scope="col" class="py-3 px-4 text-center">Created</th> --}}
                <th scope="col" class="py-3 px-4 text-center"></th>
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
                <tr class="bg-white dark:bg-gray-900{{ $loop->last ? '' : ' border-b dark:border-gray-400'}} hover:bg-gray-100 hover:dark:bg-gray-800">

                    <td class="py-3 px-4 font-bold underline hover:no-underline">
                        <a href="{{ route('exchanges.edit', $record) }}">
                            {{ $record->name }}
                        </a>
                    </td>
                    <td class="py-3 px-4">
                        {{ \Arr::get($exchanges, $record->exchange->value) }}
                    </td>
                    <td class="py-3 px-4 text-center">
                        <div class="h-2.5 w-2.5 rounded-full bg-{{$testnet_color}}-500 ml-4 mt-0.5"></div>
                    </td>
                    <td class="py-3 px-4 text-center">
                        Long: {{ number_format($lwe, 2) }}&nbsp;&nbsp;Short: {{ number_format($swe, 2) }}
                    </td>
                    <td class="py-3 px-4 text-right font-bold text-{{ $total_we > $record->max_exposure ? 'red-600' : 'green-500'}}">
                        {{ number_format($total_we, 2) }}
                    </td>
                    <td class="py-3 px-4 text-center">
                        {{ \Arr::get($risk_modes, $record->risk_mode) }}
                    </td>
                    <td class="py-3 px-4 text-center">
                        {{ $record->running_bots->count() }}/{{ $record->bots_count }}
                    </td>
                    <td class="py-3 px-4 text-center">
                        {{ $record->positions_count }}
                    </td>
                    <td class="py-3 px-4 text-center">
                        {{ $record->trades_count }}
                    </td>
                    {{-- <td class="py-3 px-4 text-center">
                        {{ $record->created_at->format('d-m-Y') }}
                    </td> --}}
                    <td class="py-3 px-4 text-right">
                        <x-btn-link class="py-1 px-2 mr-2" href="{{ route('exchanges.positions', $record) }}" >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                        </x-btn-link>
                        <x-btn-link class="py-1 px-2 mr-2" href="{{ route('exchanges.trades', $record) }}" >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </x-btn-link>
                        <x-btn-link class="py-1 px-2 mr-2" href="{{ route('exchanges.edit', $record) }}" >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </x-btn-link>
                        <x-btn-link class="py-1 px-2 mr-2" href="#tr-logs" title="Truncate logs" wire:click="truncateLogs({{ $record->id }})" >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"></path>
                            </svg>
                        </x-btn-link>
                        <x-danger-button class="py-1 px-2"
                            wire:click="deleteId({{ $record->id }})"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-exchange-deletion')"
                        >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </x-danger-button>
                    </td>
                </tr>
            @empty
                <tr class="text-center bg-white dark:bg-gray-900">
                    <td colspan="11" class="py-3 px-4 italic">No hay información</td>
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
