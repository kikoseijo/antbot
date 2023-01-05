
<div class="overflow-x-auto relative sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-4 text-center">Symbol</th>
                <th scope="col" class="py-3 px-4 text-center">Month</th>
                <th scope="col" class="py-3 px-4 text-center">Year</th>
                <th scope="col" class="py-3 px-4 text-center">Nº Trades</th>
                <th scope="col" class="py-3 px-4 text-center">PNL</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr class="bg-white dark:bg-gray-800{{ $loop->last ? '' : ' border-b dark:border-gray-400'}}">
                    <td class="py-3 px-4">
                        <span class="text-yellow-800 font-semibold dark:text-yellow-300">
                            {{ $record->symbol }}
                        </span>
                    </td>
                    <td class="py-3 px-4">
                        {{ $record->month_name }}
                    </td>
                    <td class="py-3 px-4">
                         {{ $record->year }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        {{ $record->total_trades }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        ${{ number($record->pnl) }}
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
