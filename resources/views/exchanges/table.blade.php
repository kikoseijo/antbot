
<div class="overflow-x-auto relative sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-4 text-center">Exchange</th>
                <th scope="col" class="py-3 px-4 text-center">Name</th>
                <th scope="col" class="py-3 px-4 text-center">Risk mode</th>
                <th scope="col" class="py-3 px-4 text-center">Wallet Exposure</th>
                <th scope="col" class="py-3 px-4 text-center">Total</th>
                <th scope="col" class="py-3 px-4 text-center">Bots</th>
                <th scope="col" class="py-3 px-4 text-center">Date Created</th>
                <th scope="col" class="py-3 px-4 text-center"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                @php
                    $lwe = $record->long_wallet_exposure;
                    $swe = $record->short_wallet_exposure;
                    $total_we = $swe + $lwe;
                @endphp
                <tr class="bg-white dark:bg-gray-900{{ $loop->last ? '' : ' border-b dark:border-gray-400'}}">

                    <td class="py-3 px-4">
                        {{ \Arr::get($exchanges, $record->exchange->value) }}
                    </td>
                    <td class="py-3 px-4 font-bold">
                        <a href="{{ route('exchanges.edit', $record) }}">
                            {{ $record->name }}
                        </a>
                    </td>
                    <td class="py-3 px-4 text-center">
                        {{ \Arr::get($risk_modes, $record->risk_mode) }}
                    </td>
                    <td class="py-3 px-4 text-center">
                        Long: {{ number_format($lwe, 2) }}&nbsp;&nbsp;Short: {{ number_format($swe, 2) }}
                    </td>
                    <td class="py-3 px-4 text-right font-bold text-{{ $total_we > $record->max_exposure ? 'red-600' : 'green-500'}}">
                        {{ number_format($total_we, 2) }}
                    </td>
                    <td class="py-3 px-4 text-center">
                        {{ $record->bots_count }}
                    </td>
                    <td class="py-3 px-4 text-center">
                        {{ $record->created_at->format('d-m-Y') }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        <x-btn-link class="py-1 px-2 mr-2" href="{{ route('exchanges.edit', $record) }}" >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
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
                    <td colspan="10" class="py-3 px-4 italic">No hay información</td>
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
                {{ __('Delete Account') }}
            </x-danger-button>
        </div>
    </div>
</x-modal>
