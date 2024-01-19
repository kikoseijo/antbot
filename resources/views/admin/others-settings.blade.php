<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('General settings') }}
        </h2>
    </header>
    <div class="grid grid-cols-2 grid-flow-col gap-4 mt-4 mb-4">
        <div>
            <x-input-label for="python_path" :value="__('Python path')" />
            <x-text-input id="python_path" type="text" class="mt-1 block w-full" wire:model.lazy="settings.python_path" required/>
            <x-input-error class="mt-2" :messages="$errors->get('settings.python_path')" />
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
