@php
    $entry = $row->entry_price;
    $current = $row->coin->mark_price;
    if ($entry > $current) {
        $distance = (1 - $entry / $current) * 100;
    } else {
        $distance = (1 - $current / $entry) * 100;
    }
@endphp
<div class="text-center">
    {{ number($distance, 2) }}%
</div>
