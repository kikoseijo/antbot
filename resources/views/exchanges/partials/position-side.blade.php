@php
    $side_color = $value == 'Buy' ? 'green' : 'red';
    $side_title = $value == 'Buy' ? 'Long position' : 'Short position';
@endphp
<a href="{{ route('market.trading-view', [$row, '15m']) }}">
    <div class="h-2.5 w-2.5 rounded-full bg-{{$side_color}}-500 ml-2 mt-0.5" title="{{ $side_title }}"></div>
    <span class="hidden bg-green-500 bg-red-500"></span>
</a>
