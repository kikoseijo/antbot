<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ isset($on_edit) ? __('Edit Exchange') : __('Create new Exchange API') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("You can create as many api keys as you need, there is no limitation.") }}
            <br />
            {{ __("For security reasons dont enable transfers on your apis.") }}
        </p>
    </header>
    <form wire:submit.prevent="submit" class="mt-6 space-y-6">
        <div class="grid grid-cols-3 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" type="text" class="mt-1 block w-full" wire:model.lazy="exchange.name" required autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('exchange.name')" />
            </div>
            <div>
                <x-input-label for="exchange" :value="__('Exchange')" />
                <x-select-input id="exchange" type="text" class="mt-1 block w-full" wire:model="exchange.exchange" required>
                    @foreach ($exchanges as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('exchange.exchange')" />
            </div>
            <div>
                <x-input-label for="risk_mode" :value="__('Risk Mode')" />
                <x-select-input id="risk_mode" type="text" class="mt-1 block w-full" wire:model="exchange.risk_mode" required>
                    @foreach ($risk_modes as $key => $value)
                        <option value="{{$key}}">{{$value}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('exchange.risk_mode')" />
            </div>

        </div>



        @if (!isset($on_edit))
            <div class="grid grid-cols-3 grid-flow-col gap-4 mb-6">
                <div>
                    <x-input-label for="api_key" :value="__('Api key')" />
                    <x-text-input id="api_key" type="text" class="mt-1 block w-full" wire:model.lazy="exchange.api_key" required />
                    <x-input-error class="mt-2" :messages="$errors->get('exchange.api_key')" />
                </div>
                <div>
                    <x-input-label for="api_secret" :value="__('Api secret')" />
                    <x-text-input id="api_secret" type="text" class="mt-1 block w-full" wire:model.lazy="exchange.api_secret" required />
                    <x-input-error class="mt-2" :messages="$errors->get('exchange.api_secret')" />
                </div>
            </div>
        @else
            <span class="text-md text-gray-800 dark:text-gray-400">
                <span class="font-bold">API Key:</span> <span class="mr-3">{{$exchange->api_key}}</span>
                <br />
                <span class="font-bold">API password:</span> <span class="mr-3">{{ Str::mask($exchange->api_secret, '*', 3, strlen($exchange->api_secret) - 6); }}</span>
            </span>
        @endif

        <div class="grid grid-cols-3 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="is_testnet" :value="__('Testnet enabled')" />
                <div class="flex mt-2">
                    <div class="flex items-center mr-4">
                        <input id="is_testnet_1" type="radio" value="1"  wire:model="exchange.is_testnet" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="is_testnet_1" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes</label>
                    </div>
                    <div class="flex items-center mr-4 ml-4">
                        <input id="is_testnet_2" type="radio" value="0"  wire:model="exchange.is_testnet" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="is_testnet_2" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">No</label>
                    </div>
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('exchange.risk_mode')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ isset($on_edit) ? __('Update exchange') : __('Create exchange') }}</x-primary-button>

            @if (session('status') === 'exchange-created' || session('status') === 'exchange-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 4000)"
                    class="text-sm text-green-600 dark:text-green-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
