<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ isset($on_edit) ? __('Edit config') : __('Create new config') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Have your custom grid configurations here to use with your bots.") }}
        </p>
    </header>

    <form wire:submit.prevent="submit" class="mt-6 space-y-6">

        <div class="grid grid-cols-3 grid-flow-col gap-4">
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" type="text" class="mt-1 block w-full uppercase" wire:model.defer="grid.name" required autofocus/>
                <x-input-error class="mt-2" :messages="$errors->get('grid.name')" />
            </div>
            <div class="col-span-2">
                <x-input-label for="description" :value="__('Description')" />
                <x-text-input id="description" type="text" class="mt-1 block w-full" wire:model.defer="grid.description"/>
                <x-input-error class="mt-2" :messages="$errors->get('grid.description')" />
            </div>
        </div>

        <div class="grid grid-cols-1 grid-flow-col">
            <div wire:ignore>
                <x-input-label for="code" :value="__('Grid configuration JSON')" />
                <x-textarea-input id="code" name="code" rows="32" type="text" class="mt-1 block w-full" wire:model.defer="grid.grid_json" required/>
                <x-input-error class="mt-2" :messages="$errors->get('grid.grid_json')" />
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
</section>
