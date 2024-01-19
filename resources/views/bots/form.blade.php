<section>
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

        <div class="grid grid-cols-2 grid-flow-col gap-4">
            <div class="grid grid-cols-2 grid-flow-col gap-4">
                <div>
                    <x-input-label for="grid_mode" :value="__('Strategy mode')" />
                    <x-select-input id="grid_mode" type="text" class="mt-1 block w-full" wire:model="bot.grid_mode" required>
                        @foreach ($grid_modes as $mode_id => $mode_name)
                            <option value="{{$mode_id}}">{{$mode_name}}</option>
                        @endforeach
                    </x-select-input>
                    <x-input-error class="mt-2" :messages="$errors->get('bot.grid_mode')" />
                </div>
                <div>
                    <x-input-label for="grid_id" :value="__('Strategy')" />
                    <x-select-input id="grid_id" type="text" class="mt-1 block w-full" wire:model="bot.grid_id">
                        @foreach ($my_configs as $my_grid)
                            <option value="{{$my_grid->id}}">{{$my_grid->name}}</option>
                        @endforeach
                    </x-select-input>
                    <x-input-error class="mt-2" :messages="$errors->get('bot.grid_id')" />
                </div>
            </div>
            <div>
                <div class="grid grid-cols-4 grid-flow-col gap-4">

                    <div>
                        <x-input-label for="lm" :value="__('Long Mode')" />
                        <x-select-input id="lm" type="text" class="mt-1 block w-full" wire:model="bot.lm" required>
                            @foreach ($bot_modes as $mode_id => $mode_name)
                                <option value="{{$mode_id}}">{{$mode_name}}</option>
                            @endforeach
                        </x-select-input>
                        <x-input-error class="mt-2" :messages="$errors->get('bot.lm')" />
                    </div>
                    <div>
                        <x-input-label for="lwe" :value="__('Long Exposure')" />
                        <x-text-input id="lwe" type="number" step="0.01" min="0" class="mt-1 block w-full" wire:model.lazy="bot.lwe" required/>
                        <x-input-error class="mt-2" :messages="$errors->get('bot.lwe')" />
                    </div>
                    <div>
                        <x-input-label for="sm" :value="__('Short Mode')" />
                        <x-select-input id="sm" type="text" class="mt-1 block w-full" wire:model="bot.sm" required>
                            @foreach ($bot_modes as $mode_id => $mode_name)
                                <option value="{{$mode_id}}">{{$mode_name}}</option>
                            @endforeach
                        </x-select-input>
                        <x-input-error class="mt-2" :messages="$errors->get('bot.sm')" />
                    </div>

                    <div>
                        <x-input-label for="swe" :value="__('Short Exposure')" />
                        <x-text-input id="swe" type="number" step="0.01" min="0" class="mt-1 block w-full" wire:model.lazy="bot.swe" required/>
                        <x-input-error class="mt-2" :messages="$errors->get('bot.swe')" />
                    </div>
                </div>
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
            <div class="col-span-2">
                <div class=" grid grid-cols-4 grid-flow-col gap-4 mb-6">
                    <div>
                        <x-input-label for="oh_mode" :value="__('Minute marks')" />
                        <label class="relative inline-flex items-center mr-5 mt-2 cursor-pointer">
                          <input type="checkbox" value="" class="sr-only peer" wire:model="bot.oh_mode">
                          <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-focus:ring-4 peer-focus:ring-yellow-300 dark:peer-focus:ring-yellow-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-yellow-400"></div>
                        </label>
                        <x-input-error class="mt-2" :messages="$errors->get('bot.oh_mode')" />
                    </div>
                    <div>
                        <x-input-label for="is_on_trend" :value="__('Follow Trend')" />
                        <label class="relative inline-flex items-center mr-5 mt-2 cursor-pointer">
                          <input type="checkbox" value="" class="sr-only peer" wire:model="bot.is_on_trend">
                          <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-focus:ring-4 peer-focus:ring-yellow-300 dark:peer-focus:ring-yellow-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-yellow-400"></div>
                        </label>
                        <x-input-error class="mt-2" :messages="$errors->get('bot.is_on_trend')" />
                    </div>
                    <div>
                        <x-input-label for="is_on_routines" :value="__('Routines')" />
                        <label class="relative inline-flex items-center mr-5 mt-2 cursor-pointer">
                          <input type="checkbox" value="" class="sr-only peer" wire:model="bot.is_on_routines">
                          <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-focus:ring-4 peer-focus:ring-yellow-300 dark:peer-focus:ring-yellow-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-yellow-400"></div>
                        </label>
                        <x-input-error class="mt-2" :messages="$errors->get('bot.is_on_routines')" />
                    </div>
                    <div>
                        <x-input-label for="show_logs" :value="__('Write logs')" />
                        <label class="relative inline-flex items-center mr-5 mt-2 cursor-pointer">
                          <input type="checkbox" value="" class="sr-only peer" wire:model="bot.show_logs">
                          <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-focus:ring-4 peer-focus:ring-yellow-300 dark:peer-focus:ring-yellow-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-yellow-400"></div>
                        </label>
                        <x-input-error class="mt-2" :messages="$errors->get('bot.show_logs')" />

                    </div>
                </div>
            </div>
            <div></div>

        </div>





        <div class="flex items-center gap-4">
            <x-primary-button>{{ isset($on_edit) ? __('Update') : __('Create') }}</x-primary-button>

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
