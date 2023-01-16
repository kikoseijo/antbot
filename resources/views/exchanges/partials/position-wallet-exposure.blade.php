@php
    // round(sum((POSITION.quantity*POSITION.entryPrice)/BALANCE.totalWalletBalance),3)
    if($value > 0)
        $we = (($row->size * $row->entry_price) / $value) * 100;
    else
        $we = 20;
@endphp
<div class="bg-gray-300 dark:bg-white rounded-lg h-1.5 mx-1" data-tooltip-target="tooltip-we-{{$row->id}}">
  <div class="bg-blue-500 h-1.5 rounded-lg" style="width: {{ number($we, 0) }}%"></div>
</div>
<div id="tooltip-we-{{$row->id}}" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm opacity-0 transition-opacity duration-300 tooltip dark:bg-gray-700">
    {{ number($we, 0) }}%
    <div class="tooltip-arrow" data-popper-arrow></div>
</div>
