@php
    $num_color = $value < 0 ? 'red' : 'green';
@endphp
<div class="text-right">
    <span class="text-{{ $num_color }}-300 dark:text-{{ $num_color }}-500">
        {{ number($value, 2) }}
    </span>
</div>
