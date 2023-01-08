
<div class="overflow-x-auto relative sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-4 text-center">Symbol</th>
                @foreach ($dates as $date)
                    <th scope="col" colspan="2" class="py-3 px-4 text-center">{{$date}}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($records as $symbol => $data)
                <tr class="bg-white dark:bg-gray-{{ $loop->index % 2 == 0 ? '7' : '6' }}00 border-x-2 dark:border-gray-400">
                    <td class="py-3 px-4">
                        <span class="text-yellow-800 font-semibold dark:text-yellow-300">
                            {{ $symbol }}
                        </span>
                    </td>
                    @foreach ($dates as $date)
                        @php
                            $n_trades = \Arr::get($data, $date . '.trades_count', '-');
                            $pnl_value = \Arr::get($data, $date . '.pnl', '-');
                            $pnl_color = $pnl_value >= 0 ? 'green' : 'red';
                        @endphp
                        <td class="py-3 px-4 text-center">
                            {{ $n_trades }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            @if (is_numeric($pnl_value))
                                <span class="text-{{ $pnl_color }}-300 font-semibold dark:text-{{ $pnl_color }}-500">
                                    ${{  number($pnl_value, 2) }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr class="text-center bg-white dark:bg-gray-900">
                    <td colspan="10" class="py-3 px-4 italic">Waiting for data...</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
