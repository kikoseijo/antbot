
<div class="overflow-x-auto relative sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="{{config('antbot.css.thead')}}">
            <tr>
                <th scope="col" class="py-3 px-2"></th>
                <th scope="col" class="py-3 px-2">Bot</th>
                <th scope="col" class="py-3 px-2">Symbol</th>
                <th scope="col" class="py-3 px-2">Exchange</th>
                <th scope="col" class="py-3 px-2 text-center">Market</th>
                <th scope="col" class="py-3 px-2">Grid/Config</th>
                <th scope="col" class="py-3 px-2 text-center">Long</th>
                <th scope="col" class="py-3 px-2 text-center">Short</th>
                <th scope="col" class="py-3 px-2 text-center">AB</th>
                <th scope="col" class="py-3 px-2 text-center">RunTime</th>
                <th scope="col" class="py-3 px-2 text-center"></th>
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
                    $color_running = $record->is_running ? 'yellow' : 'gray';
                @endphp
                <tr class="bg-white dark:bg-gray-900{{ $loop->last ? '' : ' border-b dark:border-gray-400'}} hover:bg-gray-100 hover:dark:bg-teal-500 hover:dark:text-white">
                    <td class="py-2 pr-2 text-center">
                        <span class="bg-teal-500 text-white text-xs ml-2 px-0.5 rounded dark:bg-teal-500 dark:text-white">
                            x{{ $record->leverage }}
                        </span>
                    </td>
                    <td class="py-2 px-2 font-bold text-left underline hover:no-underline">
                        <i class="hidden text-yellow-200"></i>
                        <a href="{{ route('bots.edit', $record) }}" class="text-{{ $color_running }}-200">
                            {{ $record->name }}
                        </a>
                    </td>
                    <td class="py-2 px-2 font-bold text-left underline hover:no-underline text-xs">
                        <a href="{{ $record->exchange_link }}" target="_blank" class="flex content-center">
                            {{ optional($record->symbol)->name }}
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"></path>
                            </svg>
                        </a>
                    </td>
                    <td class="py-2 px-2 text-left text-xs">
                          <a href="{{ route('exchanges.positions', $record->exchange) }}" class="underline hover:no-underline">
                              {{ optional($record->exchange)->name }}
                          </a>
                    </td>
                    <td class="py-2 px-2 text-center">{{ \Str::of($record->market_type->value)->ucfirst() }}</td>
                    <td class="py-2 px-2 text-xs uppercase">
                        @if ($record->grid_mode->value == 'custom' && optional($record->grid)->id > 0)
                            <a href="{{ route('configs.edit', $record->grid) }}" class="underline hover:no-underline">
                                {{ $record->grid->name }}
                            </a>
                        @else
                            {{ $record->grid_mode }}
                        @endif
                    </td>
                    <td class="py-2 px-2 text-center" data-tooltip-target="tooltip-idl-{{$record->id}}" data-tooltip-placement="left">
                        <span class="uppercase">{{ $record->lm->value }}:</span>
                        <span class="{{ $record->lm->value == 'm' ? ' line-through' : ''}}">{{ number_format($record->lwe, 2) }}</span>
                    </td>
                    <div id="tooltip-idl-{{$record->id}}" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                        {{ \Arr::get($bot_modes, $record->lm->value)}}
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <td class="py-2 px-2 text-center" data-tooltip-target="tooltip-ids-{{$record->id}}" data-tooltip-placement="left">
                        <span class="uppercase">{{ $record->sm->value }}:</span>
                        <span class="{{ $record->sm->value == 'm' ? ' line-through' : ''}}">{{ number_format($record->swe, 2) }}</span>
                    </td>
                    <div id="tooltip-ids-{{$record->id}}" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
                        {{ \Arr::get($bot_modes, $record->sm->value)}}
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <td class="py-2 px-2 text-right">
                        {!! $record->assigned_balance > 0 ? '$'.number($record->assigned_balance) : '&#8734;' !!}
                    </td>
                    <td class="py-2 px-2 text-center">
                        {{ $record->started_at ? str_replace(['hours', 'minutes'], ['h', 'mins'], $record->started_at->diffForHumans(NULL, true)) ?? 'Stopped' : '-' }}
                    </td>
                    <td class="py-2 px-2 text-right">
                        @include('bots.partials.table-action-menu', ['bot' => $record])
                    </td>
                </tr>
            @empty
                <tr class="text-center bg-white dark:bg-gray-900">
                    <td colspan="11" class="py-2 px-2 italic">No bots found</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            @foreach ($exchanges as $exchange)
                <tr class="text-xs font-semibold text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400{{ $loop->last ? '' : ' border-b dark:border-gray-400'}}">
                    <th scope="row" colspan="6" class="py-2 px-2 text-right"><span class="mr-3">{{ Str::headline($exchange->name)  }}</span></th>
                    <th scope="col" class="py-2 px-2 text-right">{{ $twel_on[$exchange->id] }}/{{ $twel[$exchange->id] }}</th>
                    <th scope="col" class="py-2 px-2 text-right">{{ $twes_on[$exchange->id] }}/{{ $twel[$exchange->id] }}</th>
                    <th scope="col" class="py-2 px-2"></th>
                    <th scope="col" class="py-2 px-2 text-right font-bold"><span class="text-green-500">{{$total_running[$exchange->id] }}</span> / <span class="text-red-600">{{ $count[$exchange->id]}}</span></th>
                    <th scope="col" class="py-2 px-2"></th>
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
