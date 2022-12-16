
<div class="overflow-x-auto relative sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-6">#</th>
                <th scope="col" class="py-3 px-6">Exchange</th>
                <th scope="col" class="py-3 px-6">Symbol</th>
                <th scope="col" class="py-3 px-6">Market type</th>
                <th scope="col" class="py-3 px-6">Grid mode</th>
                <th scope="col" class="py-3 px-6">Custom grid</th>
                <th scope="col" class="py-3 px-6">Long WE</th>
                <th scope="col" class="py-3 px-6">Short WE</th>
                <th scope="col" class="py-3 px-6">Status</th>
                <th scope="col" class="py-3 px-6"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr class="bg-white dark:bg-gray-900{{ $loop->last ? '' : ' border-b dark:border-gray-400'}}">
                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $record->id }}
                    </th>
                    <td class="py-4 px-6">{{ $record->exchange->name ?? '' }}</td>
                    <td class="py-4 px-6">{{ $record->symbol }}</td>
                    <td class="py-4 px-6">{{ $record->market_type }}</td>
                    <td class="py-4 px-6">{{ $record->grid_mode }}</td>
                    <td class="py-4 px-6">{{ $record->grid->name ?? '' }}</td>
                    <td class="py-4 px-6">{{ $record->lwe }}</td>
                    <td class="py-4 px-6">{{ $record->swe }}</td>
                    <td class="py-4 px-6">{{ $record->started_at->diffForHumans() ?? 'Stopped' }}</td>
                    <td class="py-4 px-6 text-right">
                        <x-btn-link class="py-1 px-2 mr-2" href="/bots/edit/{{ $record->id }}" >Edit</x-btn-link>
                        <x-danger-button class="py-1 px-2"
                            wire:click="deleteId({{ $record->id }})"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-bot-deletion')"
                        >{{ __('Delete') }}</x-danger-button>
                    </td>
                </tr>
            @empty
                <tr class="text-center bg-white dark:bg-gray-900">
                    <td colspan="10" class="py-4 px-6 italic">No hay información</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


<x-modal name="confirm-bot-deletion" focusable>
    <div class="p-4">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Are you sure your want to delete the bot?</h2>
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
