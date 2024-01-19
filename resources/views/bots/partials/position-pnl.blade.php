@php
    $values = [
        'l' => 0,
        's' => 0,
    ];
    $distances = [
        'l' => 0,
        's' => 0,
    ];
    foreach ($positions as $position) {
        $current = $position->coin->mark_price ?? 1;
        $distance = (1 - $position->entry_price / $current) * 100;
        if ($position->side == 'Sell') {
            $distance *= -1;
            $distances['s'] = $distance;
            $values['s'] = $position->unrealised_pnl;
        } else {
            $distances['l'] = $distance;
            $values['l'] = $position->unrealised_pnl;
        }
    }
@endphp
<div class="text-xs text-right">
    @if ($values['l'] != 0)
        @php
            $num_color_l = $values['l'] < 0 ? 'red' : 'green';
            $num_color_l = $values['l'] == 0 ? 'gray' : $num_color_l;
        @endphp
        <span class="text-xs text-{{ $num_color_l }}-500 dark:text-{{ $num_color_l }}-500">
            {{ number($values['l'], 2)}}
        </span>
        {{ number($distances['l'], 1)  }}%
    @endif
    @if ($values['s'] != 0)
        @php
            $num_color_s = $values['s'] < 0 ? 'red' : 'green';
            $num_color_s = $values['s'] == 0 ? 'gray' : $num_color_s;
        @endphp
        | <span class="text-xs text-{{ $num_color_s }}-500 dark:text-{{ $num_color_s }}-500">
            {{ number($values['s'], 2)}}
        </span>
        {{ number($distances['s'], 1)  }}%
    @endif
</div>
