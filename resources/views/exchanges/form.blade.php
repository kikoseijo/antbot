<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Create new api') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("You can create as many api keys as you need, there is no limitation.") }}
            <br />
            {{ __("For security reasons dont enable transfers on your apis.") }}
        </p>
    </header>
    <form wire:submit.prevent="submit" class="mt-6 space-y-6">
        <div class="grid grid-cols-3 grid-flow-col gap-4 mb-6">
            <div class="relative">
                <x-input-label for="exchange_name" :value="__('Name')" />
                <x-text-input id="exchange_name" name="exchange_name" type="text" class="mt-1 block w-full" wire:model="exchange_name" required autofocus autocomplete="exchange_name" />
                <x-input-error class="mt-2" :messages="$errors->get('exchange_name')" />
            </div>
            <div class="relative">
                <x-input-label for="exchange" :value="__('Exchange')" />
                <x-select-input id="exchange" name="exchange" type="text" class="mt-1 block w-full" wire:model="exchange" required autocomplete="exchange">
                    <option value="">Select exchange</option>
                    @foreach (config('antbot.exchanges') as $key => $value)
                        <option value="{{$value}}">{{$key}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('exchange')" />
            </div>
            <div class="relative">
                <x-input-label for="api_key" :value="__('Api key')" />
                <x-text-input id="api_key" name="api_key" type="text" class="mt-1 block w-full" wire:model="api_key" required autocomplete="api_key" />
                <x-input-error class="mt-2" :messages="$errors->get('api_key')" />
            </div>
        </div>

        <div class="grid grid-cols-none">
            <div class="relative">
                <x-input-label for="api_secret" :value="__('Api secret')" />
                <x-text-input id="api_secret" name="api_secret" type="text" class="mt-1 block w-full" wire:model="api_secret" required autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('api_secret')" />
            </div>
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Create exchange') }}</x-primary-button>

            @if (session('status') === 'exchange-created')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
