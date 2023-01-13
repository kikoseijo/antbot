@php
    if ($row->side == 'Buy') {
        // code...
        $num_color = $row->coin->last_price < $row->entry_price ? 'red' : 'green';
    } else {
        $num_color = $row->coin->last_price > $row->entry_price ? 'red' : 'green';

    }
@endphp
<div class="text-right">
    <span class="tex-{{ $num_color }}-300 dark:text-{{ $num_color }}-500">
        {{ number($row->coin->last_price, $row->coin->price_scale) }}
    </span>
</div>
