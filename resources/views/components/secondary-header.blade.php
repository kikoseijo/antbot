@props(['title'])

@if ($title)
    <x-slot name="header">
        <div class="grid grid-cols-2 grid-flow-col gap-4">
            <div>
                <h2 {{ $attributes->merge(['class' => 'font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight']) }}>
                    {{ $title }}
                </h2>
            </div>

            <div class="text-right">
                @auth()
                    @if (auth()->user()->exchange_id > 0)
                            <x-select-input selectItem="Exchange" id="exchange-select-id" type="text" class="w-40" wire:model="exchange.id">
                                @foreach (auth()->user()->exchanges()->orderBy('name', 'asc')->get()->pluck('name', 'id') as $exchange_id => $exchange_name)
                                    <option value="{{$exchange_id}}"{{ auth()->user()->exchange_id == $exchange_id ? ' selected' : ''}}>{{$exchange_name}}</option>
                                @endforeach
                            </x-select-input>
                        <script type="text/javascript">
                        document.getElementById('exchange-select-id').addEventListener("change", function(){
                            if (this.value != '') {
                                window.location.replace("/swap-exchange/" + this.value);
                            }
                        });
                        </script>
                    @endif
                @endauth
            </div>
        </div>
    </x-slot>
@endif
