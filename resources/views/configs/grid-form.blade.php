<form wire:submit.prevent="submit" class="mt-6 space-y-6">
    <div class="grid grid-cols-8 grid-flow-col gap-6">
        <div class="col-span-2">
            <div class="mb-3">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" type="text" class="mt-1 block w-full uppercase" wire:model.defer="grid.name" required autofocus/>
                <x-input-error class="mt-2" :messages="$errors->get('grid.name')" />
            </div>

            <div class="mb-3">
                <x-input-label for="auto_unstuck_ema_dist" :value="__('AU EMA dist')" />
                <x-text-input id="auto_unstuck_ema_dist" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.auto_unstuck_ema_dist" required/>
                <x-input-error class="mt-2" :messages="$errors->get('l_grid.auto_unstuck_ema_dist')" />
            </div>
            <div class="mb-3">
                <x-input-label for="auto_unstuck_wallet_exposure_threshold" :value="__('AU WE threshold')" />
                <x-text-input id="auto_unstuck_wallet_exposure_threshold" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.auto_unstuck_wallet_exposure_threshold" required/>
                <x-input-error class="mt-2" :messages="$errors->get('l_grid.auto_unstuck_wallet_exposure_threshold')" />
            </div>
            <div class="mb-3">
                <x-input-label for="backwards_tp" :value="__('Backwards Take profit')" />
                <x-text-input id="backwards_tp" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.backwards_tp" required/>
                <x-input-error class="mt-2" :messages="$errors->get('l_grid.backwards_tp')" />
            </div>
            <div class="mb-6">
                <x-input-label for="description" :value="__('Notes')" />
                <x-textarea-input id="description" rows="3" type="text" class="mt-1 block w-full" wire:model.defer="grid.description" />
                <x-input-error class="mt-2" :messages="$errors->get('grid.description')" />
            </div>
            <div class="">
                <img class="max-w-full h-auto rounded-lg" src="{{ asset('img/passivbot_grid_parameters.jpeg')}}" alt="Image representation" onclick="showModal('{{ asset('img/passivbot_grid_parameters.jpeg')}}')">
            </div>
        </div>
        <div class="col-span-6">
            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Chart preview</h4>
            <div id="tv-grid-edit" symbol="{{ $grid->name }}" style="width:100%; height:450px;"></div>

            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mt-3 mb-3">Primary grid</h3>
            <div class="grid grid-cols-4 grid-flow-col gap-2 mb-6">
                <div>
                    <x-input-label for="initial_qty_pct" :value="__('PBR allocation %')" />
                    <x-text-input id="initial_qty_pct" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.initial_qty_pct" required/>
                    <x-input-error class="mt-2" :messages="$errors->get('l_grid.initial_qty_pct')" />
                </div>
                <div>
                    <x-input-label for="initial_eprice_ema_dist" :value="__('initial_eprice_ema_dist')" />
                    <x-text-input id="initial_eprice_ema_dist" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.initial_eprice_ema_dist" required/>
                    <x-input-error class="mt-2" :messages="$errors->get('l_grid.initial_eprice_ema_dist')" />
                </div>
                <div>
                    <x-input-label for="ema_span_0" :value="__('EMA span 0')" />
                    <x-text-input id="ema_span_0" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.ema_span_0" required/>
                    <x-input-error class="mt-2" :messages="$errors->get('l_grid.ema_span_0')" />
                </div>
                <div>
                    <x-input-label for="ema_span_1" :value="__('EMA span 1')" />
                    <x-text-input id="ema_span_1" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.ema_span_1" required/>
                    <x-input-error class="mt-2" :messages="$errors->get('l_grid.ema_span_1')" />
                </div>
            </div>


            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Take profit</h3>
            <div class="grid grid-cols-3 grid-flow-col gap-2 mb-6">
                <div>
                    <x-input-label for="markup_range" :value="__('Markup range')" />
                    <x-text-input id="markup_range" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.markup_range" required/>
                    <x-input-error class="mt-2" :messages="$errors->get('l_grid.markup_range')" />
                </div>
                <div>
                    <x-input-label for="min_markup" :value="__('Min markup')" />
                    <x-text-input id="min_markup" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.min_markup" required/>
                    <x-input-error class="mt-2" :messages="$errors->get('l_grid.min_markup')" />
                </div>
                <div>
                    <x-input-label for="n_close_orders" :value="__('n_close_orders')" />
                    <x-text-input id="n_close_orders" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.n_close_orders" required/>
                    <x-input-error class="mt-2" :messages="$errors->get('l_grid.n_close_orders')" />
                </div>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Recursive grid</h3>
            <div class="grid grid-cols-3 grid-flow-col gap-2 mb-6">
                <div>
                    <x-input-label for="rentry_pprice_dist_wallet_exposure_weighting" :value="__('PBR allocation %')" />
                    <x-text-input id="rentry_pprice_dist_wallet_exposure_weighting" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.rentry_pprice_dist_wallet_exposure_weighting" required/>
                    <x-input-error class="mt-2" :messages="$errors->get('l_grid.rentry_pprice_dist_wallet_exposure_weighting')" />
                </div>
                <div>
                    <x-input-label for="rentry_pprice_dist" :value="__('Rentry pprice dist')" />
                    <x-text-input id="rentry_pprice_dist" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.rentry_pprice_dist" required/>
                    <x-input-error class="mt-2" :messages="$errors->get('l_grid.rentry_pprice_dist')" />
                </div>
                <div>
                    <x-input-label for="ddown_factor" :value="__('ddown_factor')" />
                    <x-text-input id="ddown_factor" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.ddown_factor" required/>
                    <x-input-error class="mt-2" :messages="$errors->get('l_grid.ddown_factor')" />
                </div>
            </div>

            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Static grid</h3>
            <div class="grid grid-cols-5 grid-flow-col gap-2 mb-6">
                <div>
                    <x-input-label for="eprice_exp_base" :value="__('eprice_exp_base')" />
                    <x-text-input id="eprice_exp_base" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.eprice_exp_base" required/>
                    <x-input-error class="mt-2" :messages="$errors->get('l_grid.eprice_exp_base')" />
                </div>
                <div>
                    <x-input-label for="eprice_pprice_diff" :value="__('eprice_pprice_diff')" />
                    <x-text-input id="eprice_pprice_diff" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.eprice_pprice_diff" required/>
                    <x-input-error class="mt-2" :messages="$errors->get('l_grid.eprice_pprice_diff')" />
                </div>
                <div>
                    <x-input-label for="grid_span" :value="__('grid_span')" />
                    <x-text-input id="grid_span" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.grid_span" required/>
                    <x-input-error class="mt-2" :messages="$errors->get('l_grid.grid_span')" />
                </div>
                <div>
                    <x-input-label for="secondary_allocation" :value="__('secondary_allocation')" />
                    <x-text-input id="secondary_allocation" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.secondary_allocation" required/>
                    <x-input-error class="mt-2" :messages="$errors->get('l_grid.secondary_allocation')" />
                </div>
                <div>
                    <x-input-label for="secondary_pprice_diff" :value="__('secondary_pprice_diff')" />
                    <x-text-input id="secondary_pprice_diff" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.secondary_pprice_diff" required/>
                    <x-input-error class="mt-2" :messages="$errors->get('l_grid.secondary_pprice_diff')" />
                </div>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Neat grid</h3>
            <div class="grid grid-cols-5 grid-flow-col gap-2 mb-6">
                <div>
                    <x-input-label for="eqty_exp_base" :value="__('eqty_exp_base')" />
                    <x-text-input id="eqty_exp_base" type="text" class="mt-1 block w-full uppercase" wire:model.defer="l_grid.eqty_exp_base" required/>
                    <x-input-error class="mt-2" :messages="$errors->get('l_grid.eqty_exp_base')" />
                </div>
            </div>
        </div>
    </div>






    <div class="flex items-center gap-4">
        <x-primary-button wire:click="submit">{{ isset($on_edit) ? __('Update config') : __('Create new config') }}</x-primary-button>
        @if (session('status') === 'grid-created' || session('status') === 'grid-updated')
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
