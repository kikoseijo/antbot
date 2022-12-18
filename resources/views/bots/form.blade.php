<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ isset($on_edit) ? __('Edit Antbot') : __('Create new Antbot') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            @if (isset($on_edit))
                {{ __("Edit your bot here, your changes will be applied upon bot restart.") }}
            @else
                {{ __("Here you can create your Antbots.") }}
            @endif
        </p>
    </header>

    <form wire:submit.prevent="submit" class="mt-6 space-y-6">

        <div class="grid grid-cols-4 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="symbol" :value="__('Symbol')" />
                <x-text-input id="symbol" type="text" class="mt-1 block w-full uppercase" wire:model.lazy="bot.symbol" required autofocus/>
                <x-input-error class="mt-2" :messages="$errors->get('bot.symbol')" />
            </div>
            <div>
                <x-input-label for="assigned_balance" :value="__('Assigned balance')" />
                <x-text-input id="assigned_balance" type="number" step="1" class="mt-1 block w-full" wire:model.lazy="bot.assigned_balance" required/>
                <x-input-error class="mt-2" :messages="$errors->get('bot.assigned_balance')" />
            </div>
        </div>
        <div class="grid grid-cols-4 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="exchange_id" :value="__('Exchange')" />
                <x-select-input id="exchange_id" type="text" class="mt-1 block w-full" wire:model="bot.exchange_id" required>
                    @foreach ($my_exchanges as $exchange)
                        <option value="{{$exchange->id}}">{{$exchange->name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('bot.exchange_id')" />
            </div>
            <div>
                <x-input-label for="market_type" :value="__('Market type')" />
                <x-select-input id="market_type" type="text" class="mt-1 block w-full" wire:model="bot.market_type" required>
                    @foreach ($market_types as $market_id => $market_name)
                        <option value="{{$market_id}}">{{$market_name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('bot.market_type')" />
            </div>
        </div>
        <div class="grid grid-cols-4 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="grid_mode" :value="__('Grid mode')" />
                <x-select-input id="grid_mode" type="text" class="mt-1 block w-full" wire:model="bot.grid_mode" required>
                    @foreach ($grid_modes as $mode_id => $mode_name)
                        <option value="{{$mode_id}}">{{$mode_name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('bot.grid_mode')" />
            </div>
            <div>
                <x-input-label for="grid_id" :value="__('Customized grid')" />
                <x-select-input id="grid_id" type="text" class="mt-1 block w-full" wire:model="bot.grid_id">
                    @foreach ($my_configs as $grid)
                        <option value="{{$grid->id}}">{{$grid->name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('bot.grid_id')" />
            </div>
        </div>

        <div class="grid grid-cols-4 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="lm" :value="__('Long mode (LM)')" />
                <x-select-input id="lm" type="text" class="mt-1 block w-full" wire:model="bot.lm" required>
                    <option value="">Select grid mode</option>
                    @foreach ($bot_modes as $mode_id => $mode_name)
                        <option value="{{$mode_id}}">{{$mode_name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('bot.lm')" />
            </div>
            <div>
                <x-input-label for="sm" :value="__('Short mode (SM)')" />
                <x-select-input id="sm" type="text" class="mt-1 block w-full" wire:model="bot.sm" required>
                    <option value="">Select grid mode</option>
                    @foreach ($bot_modes as $mode_id => $mode_name)
                        <option value="{{$mode_id}}">{{$mode_name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('bot.sm')" />
            </div>
        </div>
        <div class="grid grid-cols-4 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="lwe" :value="__('Long wallet exposure (LWE)')" />
                <x-text-input id="lwe" type="number" step="0.05" min="0" class="mt-1 block w-full" wire:model.lazy="bot.lwe" required/>
                <x-input-error class="mt-2" :messages="$errors->get('bot.lwe')" />
            </div>
            <div>
                <x-input-label for="swe" :value="__('Short wallet exposure (SWE)')" />
                <x-text-input id="swe" type="number" step="0.05" class="mt-1 block w-full" wire:model.lazy="bot.swe" required/>
                <x-input-error class="mt-2" :messages="$errors->get('bot.swe')" />
            </div>
        </div>


        <div class="flex items-center gap-4">
            <x-primary-button>{{ isset($on_edit) ? __('Update Antbot') : __('Create Antbot') }}</x-primary-button>

            @if (session('status') === 'bot-created' || session('status') === 'bot-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 4000)"
                    class="text-sm text-green-600 dark:text-green-400"
                >{{ __('Data saved.') }}</p>
            @endif
        </div>
    </form>
</section>
