<h3 class="text-lg font-medium text-gray-900 dark:text-gray-500 mt-3 mb-3">Auto unstuck + Backward take profit</h3>
<div class="grid grid-cols-3 grid-flow-col gap-2 mb-3">
    <div class="mb-3">
        <x-input-label for="auto_unstuck_ema_dist" :value="__('AU EMA dist')" />
        <x-text-input id="auto_unstuck_ema_dist" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.auto_unstuck_ema_dist" required/>
        <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.auto_unstuck_ema_dist')" />
    </div>
    <div class="mb-3">
        <x-input-label for="auto_unstuck_wallet_exposure_threshold" :value="__('AU WE threshold')" />
        <x-text-input id="auto_unstuck_wallet_exposure_threshold" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.auto_unstuck_wallet_exposure_threshold" required/>
        <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.auto_unstuck_wallet_exposure_threshold')" />
    </div>
    <div class="mb-3">
        <x-input-label for="backwards_tp" :value="__('Backwards Take profit')" />
        <x-select-input id="backwards_tp" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.backwards_tp" required>
            <option value="true">Yes</option>
            <option value="false">No</option>
        </x-select-input>
        <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.backwards_tp')" />
    </div>
</div>
<h3 class="text-lg font-medium text-gray-900 dark:text-gray-500 mb-3">Primary grid</h3>
<div class="grid grid-cols-4 grid-flow-col gap-2 mb-3">
    <div>
        <x-input-label for="initial_qty_pct" :value="__('PBR allocation %')" />
        <x-text-input id="initial_qty_pct" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.initial_qty_pct" required/>
        <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.initial_qty_pct')" />
    </div>
    <div>
        <x-input-label for="initial_eprice_ema_dist" :value="__('initial_eprice_ema_dist')" />
        <x-text-input id="initial_eprice_ema_dist" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.initial_eprice_ema_dist" required/>
        <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.initial_eprice_ema_dist')" />
    </div>
    <div>
        <x-input-label for="ema_span_0" :value="__('EMA span 0')" />
        <x-text-input id="ema_span_0" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.ema_span_0" required/>
        <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.ema_span_0')" />
    </div>
    <div>
        <x-input-label for="ema_span_1" :value="__('EMA span 1')" />
        <x-text-input id="ema_span_1" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.ema_span_1" required/>
        <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.ema_span_1')" />
    </div>
</div>


<h3 class="text-lg font-medium text-gray-900 dark:text-gray-500 mb-3">Take profit</h3>
<div class="grid grid-cols-3 grid-flow-col gap-2 mb-3">
    <div>
        <x-input-label for="markup_range" :value="__('Markup range')" />
        <x-text-input id="markup_range" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.markup_range" required/>
        <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.markup_range')" />
    </div>
    <div>
        <x-input-label for="min_markup" :value="__('Min markup')" />
        <x-text-input id="min_markup" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.min_markup" required/>
        <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.min_markup')" />
    </div>
    <div>
        <x-input-label for="n_close_orders" :value="__('n_close_orders')" />
        <x-text-input id="n_close_orders" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.n_close_orders" required/>
        <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.n_close_orders')" />
    </div>
</div>
@if ($grid->type == 'recursive')
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-500 mb-3">Recursive grid</h3>
    <div class="grid grid-cols-3 grid-flow-col gap-2 mb-3">
        <div>
            <x-input-label for="rentry_pprice_dist_wallet_exposure_weighting" :value="__('PBR allocation %')" />
            <x-text-input id="rentry_pprice_dist_wallet_exposure_weighting" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.rentry_pprice_dist_wallet_exposure_weighting" required/>
            <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.rentry_pprice_dist_wallet_exposure_weighting')" />
        </div>
        <div>
            <x-input-label for="rentry_pprice_dist" :value="__('Rentry pprice dist')" />
            <x-text-input id="rentry_pprice_dist" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.rentry_pprice_dist" required/>
            <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.rentry_pprice_dist')" />
        </div>
        <div>
            <x-input-label for="ddown_factor" :value="__('ddown_factor')" />
            <x-text-input id="ddown_factor" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.ddown_factor" required/>
            <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.ddown_factor')" />
        </div>
    </div>
@elseif ($grid->type == 'static')
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-500 mb-3">Static grid</h3>
    <div class="grid grid-cols-5 grid-flow-col gap-2 mb-3">
        <div>
            <x-input-label for="eprice_exp_base" :value="__('eprice_exp_base')" />
            <x-text-input id="eprice_exp_base" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.eprice_exp_base" required/>
            <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.eprice_exp_base')" />
        </div>
        <div>
            <x-input-label for="eprice_pprice_diff" :value="__('eprice_pprice_diff')" />
            <x-text-input id="eprice_pprice_diff" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.eprice_pprice_diff" required/>
            <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.eprice_pprice_diff')" />
        </div>
        <div>
            <x-input-label for="grid_span" :value="__('grid_span')" />
            <x-text-input id="grid_span" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.grid_span" required/>
            <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.grid_span')" />
        </div>
        <div>
            <x-input-label for="secondary_allocation" :value="__('secondary_allocation')" />
            <x-text-input id="secondary_allocation" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.secondary_allocation" required/>
            <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.secondary_allocation')" />
        </div>
        <div>
            <x-input-label for="secondary_pprice_diff" :value="__('secondary_pprice_diff')" />
            <x-text-input id="secondary_pprice_diff" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.secondary_pprice_diff" required/>
            <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.secondary_pprice_diff')" />
        </div>
    </div>
@else
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-500 mb-3">Neat grid</h3>
    <div class="grid grid-cols-5 grid-flow-col gap-2 mb-3">
        <div>
            <x-input-label for="eqty_exp_base" :value="__('eqty_exp_base')" />
            <x-text-input id="eqty_exp_base" type="text" class="mt-1 block w-full" wire:model.defer="{{$grid_mode}}.eqty_exp_base" required/>
            <x-input-error class="mt-2" :messages="$errors->get($grid_mode . '.eqty_exp_base')" />
        </div>
    </div>
@endif
