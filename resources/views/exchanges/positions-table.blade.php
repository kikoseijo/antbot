
<div class="overflow-x-auto relative sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-4 text-center">Symbol</th>
                <th scope="col" class="py-3 px-4 text-center"></th>
                <th scope="col" class="py-3 px-4 text-center">Size</th>
                <th scope="col" class="py-3 px-4 text-center">Value</th>
                <th scope="col" class="py-3 px-4 text-center">Entry</th>
                <th scope="col" class="py-3 px-4 text-center">Liq.</th>
                <th scope="col" class="py-3 px-4 text-center">Bust.</th>
                <th scope="col" class="py-3 px-4 text-center">Margin</th>
                <th scope="col" class="py-3 px-4 text-center">Reaized PNL</th>
                <th scope="col" class="py-3 px-4 text-center">Unrealized PNL</th>
                <th scope="col" class="py-3 px-4 text-center">Acc. PNL</th>
                <th scope="col" class="py-3 px-4 text-center">RiskID</th>
            </tr>
        </thead>
        <tbody>
            @forelse($positions as $record)
                <tr class="bg-white dark:bg-gray-800{{ $loop->last ? '' : ' border-b dark:border-gray-400'}}">
                    <td class="py-3 px-4 flex align-middle font-bold">
                        <div class="inline h-2.5 w-2.5 rounded-full bg-{{ $record->side == 'Buy' ? 'green' : 'red'}}-500 mr-2 mt-1"></div>
                        <a href="{{ $record->exchange_link }}" target="_blank">
                            {{ $record->symbol }}
                        </a>
                    </td>
                    <td class="py-3 px-4">
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold ml-2 px-0.5 py-0.5 rounded dark:bg-yellow-200 dark:text-yellow-900">
                          x{{ $record->leverage }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        {{ number($record->size) }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        {{ number($record->position_value) }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        {{ number($record->entry_price) }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        {{ number($record->liq_price) }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        {{ number($record->bust_price) }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        {{ number($record->position_margin) }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        {{ number($record->realised_pnl) }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        {{ number($record->unrealised_pnl) }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        {{ number($record->cum_realised_pnl) }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        {{ $record->risk_id }}
                    </td>
                </tr>
            @empty
                <tr class="text-center bg-white dark:bg-gray-900">
                    <td colspan="10" class="py-3 px-4 italic">Waiting for data...</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
