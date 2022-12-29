
<div class="overflow-x-auto relative sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="{{config('antbot.css.thead')}}">
            <tr>
                <th scope="col" class="py-3 px-4"></th>
                <th scope="col" class="py-3 px-4"></th>
                <th scope="col" class="py-3 px-4 text-center">Exchange</th>
                <th scope="col" class="py-3 px-4 text-center">Symbol</th>
                <th scope="col" class="py-3 px-4 text-center">Market type</th>
                <th scope="col" class="py-3 px-4 text-center">Grid/Config</th>
                <th scope="col" class="py-3 px-4 text-center">Long WE</th>
                <th scope="col" class="py-3 px-4 text-center">Short WE</th>
                <th scope="col" class="py-3 px-4 text-center">RunTime</th>
                <th scope="col" class="py-3 px-4 text-center"></th>
            </tr>
        </thead>
        <tbody>
            @php
                $exchanges = [];
                $twes = [];
                $twel = [];
                $twes_on = [];
                $twel_on = [];
                $count = [];
                $total_running = [];
            @endphp
            @forelse($records as $record)
                @php
                    $exchange = $record->exchange;
                    if (!\Arr::has($count, $exchange->id)) {
                        $count[$exchange->id] = 0;
                    }
                    if (!\Arr::has($twel, $exchange->id)) {
                        $twel[$exchange->id] = 0;
                    }
                    if (!\Arr::has($twes, $exchange->id)) {
                        $twes[$exchange->id] = 0;
                    }
                    if (!\Arr::has($twes_on, $exchange->id)) {
                        $twes_on[$exchange->id] = 0;
                    }
                    if (!\Arr::has($twel_on, $exchange->id)) {
                        $twel_on[$exchange->id] = 0;
                    }
                    if (!\Arr::has($count, $exchange->id)) {
                        $count[$exchange->id] = 0;
                    }
                    if (!\Arr::has($total_running, $exchange->id)) {
                        $total_running[$exchange->id] = 0;
                    }
                    if (!in_array($exchange, $exchanges)){
                        array_push($exchanges, $exchange);
                    }
                    $count[$exchange->id] += 1;
                    $twel[$exchange->id] += $record->lwe;
                    $twes[$exchange->id] += $record->swe;
                    if ($record->is_running) {
                        $total_running[$exchange->id] += 1;
                        if ($record->sm->value != 'm') {
                            $twes_on[$exchange->id] += $record->swe;
                        }
                        if ($record->lm->value != 'm') {
                            $twel_on[$exchange->id] += $record->lwe;
                        }
                    }
                @endphp
                <tr class="bg-white dark:bg-gray-900{{ $loop->last ? '' : ' border-b dark:border-gray-400'}}">
                    <th scope="row" class="py-2 px-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        @if ($record->is_running)
                            <div class="h-2.5 w-2.5 rounded-full bg-green-500 mr-2"></div>
                        @else
                            <div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></div>
                        @endif
                    </th>
                    <td class="py-2 px-4 text-center">
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold ml-2 px-0.5 py-0.5 rounded dark:bg-yellow-200 dark:text-yellow-900">
                          x{{ $record->leverage }}
                        </span>
                    </td>
                    <td class="py-2 px-4 text-center">
                        <a href="https://www.bybit.com/trade/usdt/{{$record->symbol}}" target="_blank">
                          {{ $record->exchange->name }}
                        </a>
                    </td>
                    <td class="py-2 px-4 font-bold text-center">
                        <a href="{{ route('bots.edit', $record) }}">
                             {{ $record->symbol }}
                        </a>
                    </td>
                    <td class="py-2 px-4 text-center">{{ \Str::of($record->market_type->value)->ucfirst() }}</td>
                    <td class="py-2 px-4 text-center">{{ $record->grid_mode->value == 'custom' ? $record->grid->name : $record->grid_mode }}</td>
                    <td class="py-2 px-4 text-center" data-tooltip-target="tooltip-idl-{{$record->id}}" data-tooltip-placement="left">
                        <span class="uppercase">{{ $record->lm->value }}:</span>
                        <span class="{{ $record->lm->value == 'm' ? ' line-through' : ''}}">{{ number_format($record->lwe, 2) }}</span>
                    </td>
                    <div id="tooltip-idl-{{$record->id}}" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                        {{ \Arr::get($bot_modes, $record->lm->value)}}
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <td class="py-2 px-4 text-center" data-tooltip-target="tooltip-ids-{{$record->id}}" data-tooltip-placement="left">
                        <span class="uppercase">{{ $record->sm->value }}:</span>
                        <span class="{{ $record->sm->value == 'm' ? ' line-through' : ''}}">{{ number_format($record->swe, 2) }}</span>
                    </td>
                    <div id="tooltip-ids-{{$record->id}}" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                        {{ \Arr::get($bot_modes, $record->sm->value)}}
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <td class="py-2 px-4 text-right">{{ $record->started_at ? $record->started_at->diffForHumans() ?? 'Stopped' : '-' }}</td>
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
            @foreach ($exchanges as $exchange)
                <tr class="text-xs font-semibold text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400{{ $loop->last ? '' : ' border-b dark:border-gray-400'}}">
                    <th scope="row" colspan="6" class="py-3 px-4 text-right"><span class="mr-3">{{ Str::headline($exchange->name)  }}</span></th>
                    <th scope="col" class="py-3 px-4 text-right">{{ $twel_on[$exchange->id] }}/{{ $twel[$exchange->id] }}</th>
                    <th scope="col" class="py-3 px-4 text-right">{{ $twes_on[$exchange->id] }}/{{ $twel[$exchange->id] }}</th>
                    <th scope="col" class="py-3 px-4 text-right font-bold"><span class="text-green-500">{{$total_running[$exchange->id] }}</span> / <span class="text-red-600">{{ $count[$exchange->id]}}</span></th>
                    <th scope="col" class="py-3 px-4"></th>
                </tr>
            @endforeach
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
