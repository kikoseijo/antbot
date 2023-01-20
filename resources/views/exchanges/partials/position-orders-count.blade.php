<a href="{{ route('market.trading-view', [$row, '15m']) }}">
    <div class=" text-right">
        <span class="text-green-500 dark:text-green-500">
            {{ $row->buy_orders->count() }}
        </span>/<span class="text-red-500 dark:text-red-500">
            {{ $row->sell_orders->count() }}
        </span>
    </div>
</a>
