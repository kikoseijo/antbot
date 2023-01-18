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

        <x-input-error class="mt-2" :messages="$errors->get('bot_limits')" />

        <div class="grid grid-cols-4 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="name" :value="__('Bot name')" />
                <x-text-input id="name" type="text" class="mt-1 block w-full" wire:model.lazy="bot.name" required autofocus/>
                <x-input-error class="mt-2" :messages="$errors->get('bot.name')" />
            </div>
            <div>
                <x-input-label for="symbol_id" :value="__('Symbol')" />
                <x-select-input id="market_type" type="text" class="mt-1 block w-full" wire:model="bot.symbol_id" required>
                    @foreach ($symbols as $symb_id => $symb_name)
                        <option value="{{$symb_id}}">{{ str_replace('_UMCBL', '', $symb_name) }}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('bot.symbol_id')" />
            </div>

        </div>
        <div class="grid grid-cols-4 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="market_type" :value="__('Market type')" />
                <x-select-input id="market_type" type="text" class="mt-1 block w-full" wire:model="bot.market_type" required>
                    @foreach ($market_types as $market_id => $market_name)
                        <option value="{{$market_id}}">{{$market_name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('bot.market_type')" />
            </div>
            <div>
                <x-input-label for="assigned_balance" :value="__('Assigned balance')" />
                <x-text-input id="assigned_balance" type="number" step="0.00001" class="mt-1 block w-full" wire:model.lazy="bot.assigned_balance" required/>
                <x-input-error class="mt-2" :messages="$errors->get('bot.assigned_balance')" />
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
                <x-input-label for="grid_id" :value="__('Custom grid')" />
                <x-select-input id="grid_id" type="text" class="mt-1 block w-full" wire:model="bot.grid_id">
                    @foreach ($my_configs as $my_grid)
                        <option value="{{$my_grid->id}}">{{$my_grid->name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('bot.grid_id')" />
            </div>
        </div>

        <div class="grid grid-cols-4 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="lm" :value="__('Long mode (LM)')" />
                <x-select-input id="lm" type="text" class="mt-1 block w-full" wire:model="bot.lm" required>
                    @foreach ($bot_modes as $mode_id => $mode_name)
                        <option value="{{$mode_id}}">{{$mode_name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('bot.lm')" />
            </div>
            <div>
                <x-input-label for="sm" :value="__('Short mode (SM)')" />
                <x-select-input id="sm" type="text" class="mt-1 block w-full" wire:model="bot.sm" required>
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
                <x-text-input id="lwe" type="number" step="0.01" min="0" class="mt-1 block w-full" wire:model.lazy="bot.lwe" required/>
                <x-input-error class="mt-2" :messages="$errors->get('bot.lwe')" />
            </div>
            <div>
                <x-input-label for="swe" :value="__('Short wallet exposure (SWE)')" />
                <x-text-input id="swe" type="number" step="0.01" min="0" class="mt-1 block w-full" wire:model.lazy="bot.swe" required/>
                <x-input-error class="mt-2" :messages="$errors->get('bot.swe')" />
            </div>
        </div>
        <div class="grid grid-cols-4 grid-flow-col gap-4 mb-6">
            @php
                $min_lev = optional($bot->symbol)->min_leverage;
                $max_lev = optional($bot->symbol)->max_leverage;
            @endphp
            <div>
                <x-input-label for="leverage" :value="__('Exchange leverage')" />
                <x-text-input id="leverage" type="number" step="1" min="{{$min_lev}}" max="{{$max_lev}}" class="mt-1 block w-full" wire:model.lazy="bot.leverage" required/>
                <x-input-error class="mt-2" :messages="$errors->get('bot.leverage')" />
            </div>
            <div>
                <x-input-label for="show_logs" :value="__('Write logs')" />
                <div class="flex mt-2">
                    <div class="flex items-center mr-4">
                        <input id="show_logs_1" type="radio" value="1"  wire:model="bot.show_logs" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="show_logs_1" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes</label>
                    </div>
                    <div class="flex items-center mr-4 ml-4">
                        <input id="show_logs_2" type="radio" value="0"  wire:model="bot.show_logs" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="show_logs_2" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">No</label>
                    </div>
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('bot.show_logs')" />
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
        <input type="hidden" name="bot_limits" id="bot_limits" wire:model.lazy="bot_limits" value="10">
    </form>
</section>
