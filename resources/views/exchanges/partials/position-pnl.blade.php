@php
    $num_color = $value < 0 ? 'red' : 'green';
    $num_color = $value == 0 ? 'gray' : $num_color;
@endphp
<div class="text-right">
    <span class="tex-{{ $num_color }}-300 dark:text-{{ $num_color }}-500">
        {{ $value != 0 ? number($value, 2) : '-' }}
    </span>
</div>
