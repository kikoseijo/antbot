<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Main settings') }}
        </h2>
    </header>
    <div class="grid grid-cols-5 grid-flow-col gap-4 mb-4 mt-4">
        @foreach (config('antbot.modules') as $module)
            @php
                $module_name = \Str::title(str_replace('_',' ',$module));
            @endphp
            <div>
                <x-input-label for="{{ $module }}" :value="__($module_name)" />
                <label class="relative inline-flex items-center mr-5 mt-2 cursor-pointer">
                    <input type="checkbox" value="" class="sr-only peer" wire:model="settings.{{ $module }}">
                    <div class="w-11 h-6 bg-gray-200 rounded-full peer dark:bg-gray-700 peer-focus:ring-4 peer-focus:ring-yellow-300 dark:peer-focus:ring-yellow-800 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-yellow-400"></div>
                </label>
                <x-input-error class="mt-2" :messages="$errors->get('settings.{{ $module }}')" />
            </div>
        @endforeach
    </div>
    <div class="grid grid-cols-4 grid-flow-col gap-4 mb-4">
        <div>
            <x-input-label for="antbot_branch" :value="__('Update from branch')" />
            <x-select-input id="antbot_branch" type="text" class="mt-1 block w-full" wire:model="settings.antbot_branch" required>
                @for ($i=1; $i < count($branches); $i++)
                    <option value="{{$branches[$i]}}">{{ $branches[$i] }}</option>
                @endfor
            </x-select-input>
            <x-input-error class="mt-2" :messages="$errors->get('settings.antbot_branch')" />
        </div>
        <div>
            <x-input-label for="exchange_max_bots" :value="__('Grid limits per exchange creation')" />
            <x-text-input id="exchange_max_bots" type="number" class="mt-1 block w-full" wire:model.lazy="settings.exchange_max_bots" required/>
            <x-input-error class="mt-2" :messages="$errors->get('settings.exchange_max_bots')" />
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
