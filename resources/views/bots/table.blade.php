
<div class="overflow-x-auto relative sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-4">#</th>
                <th scope="col" class="py-3 px-4">Exchange</th>
                <th scope="col" class="py-3 px-4">Symbol</th>
                <th scope="col" class="py-3 px-4">Market type</th>
                <th scope="col" class="py-3 px-4">Grid/Config</th>
                <th scope="col" class="py-3 px-4">Long WE</th>
                <th scope="col" class="py-3 px-4">Short WE</th>
                <th scope="col" class="py-3 px-4">RunTime</th>
                <th scope="col" class="py-3 px-4"></th>
            </tr>
        </thead>
        <tbody>
            @php
                $twes = 0;
                $twel = 0;
                $count = 0;
                $total_running = 0;
            @endphp
            @forelse($records as $record)
                @php
                    $count++;
                    $twel += $record->lwe;
                    $twes += $record->swe;
                    $status_color = 'text-red-500 dark:text-red-500';
                    if ($record->is_running) {
                        $status_color =  'text-green-500 dark:text-green-500';
                        $total_running++;
                    }
                @endphp
                <tr class="bg-white dark:bg-gray-900{{ $loop->last ? '' : ' border-b dark:border-gray-400'}}">
                    <th scope="row" class="py-2 px-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $record->id }}
                    </th>
                    <td class="py-2 px-4">
                        <span class="bg-gray-100 text-gray-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">
                          x{{ $record->leverage }}
                        </span>
                        {{ \Str::headline($record->exchange->name) }}
                    </td>
                    <td class="py-2 px-4 font-bold {{$status_color}}">
                        <a href="{{ route('bots.edit', $record) }}">
                            {{ $record->symbol }}
                        </a>
                    </td>
                    <td class="py-2 px-4">{{ $record->market_type }}</td>
                    <td class="py-2 px-4">{{ $record->grid_mode->value == 'custom' ? $record->grid->name : $record->grid_mode }}</td>
                    <td class="py-2 px-4{{ $record->lm->value == 'm' ? ' line-through' : ''}}">{{ $record->lwe }}</td>
                    <td class="py-2 px-4{{ $record->sm->value == 'm' ? ' line-through' : ''}}">{{ $record->swe }}</td>
                    <td class="py-2 px-4">{{ $record->started_at ? $record->started_at->diffForHumans() ?? 'Stopped' : '-' }}</td>
                    <td class="py-2 px-4 text-right">
                        @include('partials.bot-table-menu', ['bot' => $record])
                    </td>
                </tr>
            @empty
                <tr class="text-center bg-white dark:bg-gray-900">
                    <td colspan="10" class="py-2 px-4 italic">No hay información</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="font-semibold text-gray-900 dark:text-white">
                <th scope="row" colspan="2" class="py-3 px-4 text-base text-left">Totals</th>
                <th scope="col" class="py-3 px-4"></th>
                <th scope="col" class="py-3 px-4"></th>
                <th scope="col" class="py-3 px-4"></th>
                <th scope="col" class="py-3 px-4">{{ $twel }}</th>
                <th scope="col" class="py-3 px-4">{{ $twes }}</th>
                <th scope="col" class="py-3 px-4">Running: {{$total_running . '/' . $count}}</th>
                <th scope="col" class="py-3 px-4"></th>
            </tr>
        </tfoot>
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
                {{ __('Delete') }}
            </x-danger-button>
        </div>
    </div>
</x-modal>
