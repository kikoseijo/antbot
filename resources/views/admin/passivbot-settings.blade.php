<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Passivbot settings') }}
        </h2>
    </header>
    <div class="grid grid-cols-2 grid-flow-col gap-4 mb-4 mt-4">
        <div>
            <x-input-label for="passivbot_path" :value="__('Passivbot path')" />
            <x-text-input id="passivbot_path" type="text" class="mt-1 block w-full" wire:model.lazy="settings.passivbot_path" required/>
            <x-input-error class="mt-2" :messages="$errors->get('settings.passivbot_path')" />
        </div>
        <div>
            <x-input-label for="passivbot_logs_path" :value="__('Bots logs path')" />
            <x-text-input id="passivbot_logs_path" type="text" class="mt-1 block w-full" wire:model.lazy="settings.passivbot_logs_path" required/>
            <x-input-error class="mt-2" :messages="$errors->get('settings.passivbot_logs_path')" />
        </div>
    </div>
    <div class="grid grid-cols-1 grid-flow-col gap-4 mb-4">
        <div>
            <x-input-label for="passivbot_grid_neat" :value="__('Neat grid mode')" />
            <x-text-input id="passivbot_grid_neat" type="text" class="mt-1 block w-full" wire:model.lazy="settings.passivbot_grid_neat" required/>
            <x-input-error class="mt-2" :messages="$errors->get('settings.passivbot_grid_neat')" />
        </div>
    </div>
    <div class="grid grid-cols-1 grid-flow-col gap-4 mb-4">
        <div>
            <x-input-label for="passivbot_grid_recursive" :value="__('Recursive grid mode')" />
            <x-text-input id="passivbot_grid_recursive" type="text" class="mt-1 block w-full" wire:model.lazy="settings.passivbot_grid_recursive" required/>
            <x-input-error class="mt-2" :messages="$errors->get('settings.passivbot_grid_recursive')" />
        </div>
    </div>
    <div class="grid grid-cols-1 grid-flow-col gap-4 mb-4">
        <div>
            <x-input-label for="passivbot_grid_static" :value="__('Static grid mode')" />
            <x-text-input id="passivbot_grid_static" type="text" class="mt-1 block w-full" wire:model.lazy="settings.passivbot_grid_static" required/>
            <x-input-error class="mt-2" :messages="$errors->get('settings.passivbot_grid_static')" />
        </div>
    </div>
    <div class="grid grid-cols-1 grid-flow-col gap-4 mb-4">
        <div>
            <x-input-label for="passivbot_grid_clock" :value="__('Clock mode')" />
            <x-text-input id="passivbot_grid_clock" type="text" class="mt-1 block w-full" wire:model.lazy="settings.passivbot_grid_clock" required/>
            <x-input-error class="mt-2" :messages="$errors->get('settings.passivbot_grid_clock')" />
        </div>
    </div>
    <div class="flex items-center gap-4">
        <x-primary-button>{{ __('Save') }}</x-primary-button>

        @if (session('status') === 'settings-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="text-sm text-gray-600 dark:text-gray-400"
            >{{ __('Saved.') }}</p>
        @endif
    </div>
</section>
