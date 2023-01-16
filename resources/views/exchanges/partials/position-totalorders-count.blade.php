@php
    $total_sale = 0;
    $total_buy = 0;
    foreach ($rows as $row) {
        $total_buy += $row->buy_orders->count();
        $total_sale += $row->sell_orders->count();
    }
@endphp
<div class=" text-right">
    <span class="text-green-500 dark:text-green-500">
        {{ $total_buy }}
    </span>/<span class="text-red-500 dark:text-red-500">
        {{ $total_sale }}
    </span>
</div>
