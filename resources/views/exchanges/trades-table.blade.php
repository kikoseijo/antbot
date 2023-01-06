
<div class="overflow-x-auto relative sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-4 text-center">Symbol</th>
                <th scope="col" class="py-3 px-4 text-center">Nº Trades</th>
                <th scope="col" class="py-3 px-4 text-center">PNL</th>
            </tr>
        </thead>
        <tbody>
            @php
                $cur_month = '';
                $cur_year = '';
            @endphp
            @forelse($records as $record)
                @php
                    $pnl_color = $record->pnl >= 0 ? 'green' : 'red';
                @endphp
                @if ($cur_month != $record->month || $cur_year != $record->year)
                    <tr class="bg-white dark:bg-gray-900">
                        <th colspan="3" class="py-3 px-4 text-center uppercase">
                            {{ $record->month_name }} - {{ $record->year }}
                        </th>
                    </tr>
                @endif
                <tr class="bg-white dark:bg-gray-{{ $loop->index % 2 == 0 ? '7' : '6' }}00 border-x-2 dark:border-gray-400">
                    <td class="py-3 px-4">
                        <span class="text-yellow-800 font-semibold dark:text-yellow-300">
                            {{ $record->symbol }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        {{ $record->total_trades }}
                    </td>
                    <td class="py-3 px-4 text-right">
                        <span class="text-{{ $pnl_color }}-300 font-semibold dark:text-{{ $pnl_color }}-500">
                            ${{ number($record->pnl) }}
                        </span>
                    </td>
                </tr>
                @php
                    $cur_month = $record->month;
                    $cur_year = $record->year;
                @endphp
            @empty
                <tr class="text-center bg-white dark:bg-gray-900">
                    <td colspan="10" class="py-3 px-4 italic">Waiting for data...</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
