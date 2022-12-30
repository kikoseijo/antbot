@php
    $entry = $row->entry_price;
    $current = $row->coin->mark_price;
    $distance = (1 - $entry / $current) * 100;
    // if ($entry > $current) {
    // } else {
    //     $distance = (1 - $current / $entry) * 100;
    // }
@endphp
<div class="text-center">
    {{ number($distance, 2) }}%
</div>
