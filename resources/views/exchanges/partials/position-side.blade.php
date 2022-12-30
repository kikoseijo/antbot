@php
    $color = $value == 'Buy' ? 'green' : 'red';
@endphp
<a href="{{ route('market.trading-view', [$row, '15m']) }}">
    <div class="h-2.5 w-2.5 rounded-full bg-{{$color}}-500 ml-2 mt-0.5"></div>
</a>
