
<div class="overflow-x-auto relative sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-4 text-center w-20">Date</th>
                <th scope="col" class="py-3 px-4 text-center w-20">Time</th>
                <th scope="col" class="py-3 px-4 text-center">Symbol</th>
                <th scope="col" class="py-3 px-4 text-center">Lev.</th>
                <th scope="col" class="py-3 px-4 text-center w-20">Side</th>
                <th scope="col" class="py-3 px-4 text-center w-20">PNL</th>
            </tr>
        </thead>
        <tbody>
          @php
            $total_vol = 0;
            $total['Buy'] = 0;
            $total['Sell'] = 0;
          @endphp
            @forelse($records as $record)
              @php
                  $total[$record->side] += $record->closed_pnl;
                  $total_vol += $record->closed_pnl < 0 ? $record->closed_pnl * -1 : $record->closed_pnl;
                  $pnl_color = $record->closed_pnl >= 0 ? 'green' : 'red';
                  $side_color = $record->side == 'Buy' ? 'green' : 'red';
              @endphp
              <tr class="bg-white {{ $loop->index % 2 == 0 ? 'bg-gray-100' : ''}} dark:bg-gray-900{{ $loop->last ? '' : ' border-b dark:border-gray-400'}} hover:bg-teal-100 hover:dark:bg-[#080C19] hover:dark:text-white">
                    <td class="py-3 px-4">
                        {{ $record->created_at->format('d M Y') }}
                    </td>
                    <td class="py-3 px-4">
                        {{ $record->created_at->format('H:i') }}
                    </td>
                    <td class="py-3 px-4 text-center">
                        {{ $record->symbol }}
                    </td>
                    <td class="py-3 px-4 text-center">
                        {{ $record->leverage }}x
                    </td>
                    <td class="py-3 px-4 text-center">
                      <span class="text-{{ $side_color }}-300 font-semibold dark:text-{{ $side_color }}-500">
                        {{ $record->side == 'Buy' ? 'Long' : 'Short' }}
                      </span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <span class="text-{{ $pnl_color }}-300 font-semibold dark:text-{{ $pnl_color }}-500">
                            ${{ number($record->closed_pnl) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr class="text-center bg-white dark:bg-gray-900">
                    <td colspan="6" class="py-3 px-4 italic">Waiting for data...</td>
                </tr>
            @endforelse
        </tbody>
        @php
          $total_color = $total_vol >= 0 ? 'gray' : 'gray';
          $buy_color = $total['Buy'] >= 0 ? 'green' : 'red';
          $sell_color = $total['Sell'] >= 0 ? 'green' : 'red';
        @endphp
        <tfoot>
            <tr class="text-xs font-semibold text-gray-700 uppercase bg-gray-50 dark:bg-gray-900 dark:text-gray-400">
                <th scope="row" class="py-2 px-2 text-right">Totals:</th>
                    <th scope="col" colspan="5" class="py-2 px-2 text-right">
                      Long: <span class="text-{{ $buy_color }}-300 font-semibold dark:text-{{ $buy_color }}-500">
                          ${{ number($total['Buy']) }}
                      </span>&nbsp;
                      Short: <span class="text-{{ $sell_color }}-300 font-semibold dark:text-{{ $sell_color }}-500">
                          ${{ number($total['Sell']) }}
                      </span>&nbsp;
                      VOLUME: <span class="text-{{ $total_color }}-300 font-semibold dark:text-{{ $total_color }}-500">
                          ${{ number($total_vol) }}
                      </span>
                    </th>
            </tr>
        </tfoot>

    </table>
</div>
