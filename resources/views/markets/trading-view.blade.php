<div class="text-white">
    <div id="tv-grid-edit"
        symbol="{{ $position->symbol }}"
        interval="{{ $interval }}"
        precision="{{ $precision }}"
        orders="{{ json_encode($orders) }}"
        entry-order="{{ json_encode($entry_order) }}"
        style="width:100%; height:450px;"></div>
</div>

@push('scripts')
    @vite(['resources/js/tradingview.js'])
@endpush
