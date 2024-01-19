<x-select-input id="exchange-select-id" type="text" class="dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600" wire:model="exchange.id">
    @foreach ($exchange->user->exchanges()->orderBy('name')->get()->pluck('name', 'id') as $exchange_id => $exchange_name)
        <option value="{{$exchange_id}}">{{$exchange_name}}</option>
    @endforeach
</x-select-input>


<script type="text/javascript">
    document.getElementById('exchange-select-id').addEventListener("change", function(){
        setTimeout(function(){
            Livewire.emit('refreshDatatable');
        }, 500);
    });
</script>
