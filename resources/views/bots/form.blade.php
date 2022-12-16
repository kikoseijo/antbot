<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Create new Antbot') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Here you can create your Antbots.") }}
        </p>
    </header>

    <form wire:submit.prevent="submit" class="mt-6 space-y-6">

        <div class="grid grid-cols-4 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="symbol" :value="__('Symbol')" />
                <x-text-input id="symbol" name="symbol" type="text" class="mt-1 block w-full uppercase" wire:model="symbol" required autofocus autocomplete="symbol" />
                <x-input-error class="mt-2" :messages="$errors->get('symbol')" />
            </div>
            <div>
                <x-input-label for="assigned_balance" :value="__('Assigned balance')" />
                <x-text-input id="assigned_balance" name="assigned_balance" type="number" step="1" class="mt-1 block w-full" wire:model="assigned_balance" required autocomplete="assigned_balance" />
                <x-input-error class="mt-2" :messages="$errors->get('assigned_balance')" />
            </div>
        </div>
        <div class="grid grid-cols-4 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="exchange" :value="__('Exchange')" />
                <x-select-input id="exchange" name="exchange" type="text" class="mt-1 block w-full" wire:model="exchange" required>
                    @foreach ($my_exchanges as $exchange)
                        <option value="{{$exchange->id}}">{{$exchange->name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('exchange')" />
            </div>
            <div>
                <x-input-label for="market_type" :value="__('Market type')" />
                <x-select-input id="market_type" name="market_type" type="text" class="mt-1 block w-full" wire:model="market_type" required>
                    @foreach ($market_types as $market_id => $market_name)
                        <option value="{{$market_id}}">{{$market_name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('grid')" />
            </div>
        </div>
        <div class="grid grid-cols-4 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="grid_mode" :value="__('Grid mode')" />
                <x-select-input id="grid_mode" name="grid_mode" type="text" class="mt-1 block w-full" wire:model="grid_mode" required>
                    @foreach ($grid_modes as $mode_id => $mode_name)
                        <option value="{{$mode_id}}">{{$mode_name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('grid_mode')" />
            </div>
            <div>
                <x-input-label for="grid" :value="__('Custom grid')" />
                <x-select-input id="grid" name="grid" type="text" class="mt-1 block w-full" wire:model="grid">
                    @foreach ($my_configs as $grid)
                        <option value="{{$grid->id}}">{{$grid->name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('grid')" />
            </div>
        </div>

        <div class="grid grid-cols-4 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="long_mode" :value="__('Long mode (LM)')" />
                <x-select-input id="long_mode" name="long_mode" type="text" class="mt-1 block w-full" wire:model="long_mode" required>
                    <option value="">Select grid mode</option>
                    @foreach ($bot_modes as $mode_id => $mode_name)
                        <option value="{{$mode_id}}">{{$mode_name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('long_mode')" />
            </div>
            <div>
                <x-input-label for="short_mode" :value="__('Short mode (SM)')" />
                <x-select-input id="short_mode" name="short_mode" type="text" class="mt-1 block w-full" wire:model="short_mode" required>
                    <option value="">Select grid mode</option>
                    @foreach ($bot_modes as $mode_id => $mode_name)
                        <option value="{{$mode_id}}">{{$mode_name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('short_mode')" />
            </div>
        </div>
        <div class="grid grid-cols-4 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="long_wallet_exposure" :value="__('Long wallet exposure (LWE)')" />
                <x-text-input id="long_wallet_exposure" name="long_wallet_exposure" type="number" step="0.05" min="0" class="mt-1 block w-full" wire:model="long_wallet_exposure" required autocomplete="long_wallet_exposure" />
                <x-input-error class="mt-2" :messages="$errors->get('long_wallet_exposure')" />
            </div>
            <div>
                <x-input-label for="short_wallet_exposure" :value="__('Short wallet exposure (SWE)')" />
                <x-text-input id="short_wallet_exposure" name="short_wallet_exposure" type="number" step="0.05" class="mt-1 block w-full" wire:model="short_wallet_exposure" required autocomplete="short_wallet_exposure" />
                <x-input-error class="mt-2" :messages="$errors->get('short_wallet_exposure')" />
            </div>
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Create Antbot') }}</x-primary-button>

            @if (session('status') === 'bot-created')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Created.') }}</p>
            @endif
        </div>
    </form>
</section>
