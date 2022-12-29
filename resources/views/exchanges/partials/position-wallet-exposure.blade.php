@php
    // round(sum((POSITION.quantity*POSITION.entryPrice)/BALANCE.totalWalletBalance),3)
    $we = ($row->size * $row->entry_price) / $value;
@endphp
<div class="text-center">
    {{ number($we, 3) }}%
</div>
