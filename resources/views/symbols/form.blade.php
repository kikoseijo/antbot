<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ isset($on_edit) ? __('Edit Symbol') : __('Create new Symbol') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            @if (isset($on_edit))
                {{ __("Edit your symbol here.") }}
            @else
                {{ __("Here you can create your Symbols.") }}
            @endif
        </p>
    </header>

    <form wire:submit.prevent="submit" class="mt-6 space-y-6">

        <x-input-error class="mt-2" :messages="$errors->get('symbol_limits')" />

        <div class="grid grid-cols-3 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="name" :value="__('Symbol name')" />
                <x-text-input id="name" type="text" class="mt-1 block w-full" wire:model.lazy="symbol.name" required autofocus/>
                <x-input-error class="mt-2" :messages="$errors->get('symbol.name')" />
            </div>
            <div>
                <x-input-label for="market" :value="__('Market')" />
                <x-select-input id="market" type="text" class="mt-1 block w-full" wire:model="symbol.market" required>
                    @foreach ($market_types as $market_id => $market_name)
                        <option value="{{$market_id}}">{{$market_name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('symbol.market')" />
            </div>
            <div>
                <x-input-label for="exchange" :value="__('Exchange')" />
                <x-select-input id="exchange" type="text" class="mt-1 block w-full" wire:model="symbol.exchange" required>
                    @foreach ($exchanges as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('symbol.exchange')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ isset($on_edit) ? __('Update Symbol') : __('Create Symbol') }}</x-primary-button>

            @if (session('status') === 'symbol-created' || session('status') === 'symbol-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 4000)"
                    class="text-sm text-green-600 dark:text-green-400"
                >{{ __('Data saved.') }}</p>
            @endif
        </div>
        <input type="hidden" name="symbol_limits" id="symbol_limits" wire:model.lazy="symbol_limits" value="10">
    </form>
</section>
