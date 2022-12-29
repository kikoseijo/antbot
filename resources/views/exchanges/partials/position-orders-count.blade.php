<div class="font-bold text-right">
    <span class="tex-green-300 dark:text-green-500">
        {{ $row->buy_orders()->count() }}
    </span>/<span class="tex-red-300 dark:text-red-500">
        {{ $row->sell_orders()->count() }}
    </span>
</div>
