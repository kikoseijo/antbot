<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ isset($on_edit) ? __('Edit Routine') : __('Create new Routine') }}
        </h2>

    </header>

    <form wire:submit.prevent="submit" class="mt-6 space-y-6">
        <div class="grid grid-cols-4 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="name" :value="__('Routine name')" />
                <x-text-input id="name" type="text" class="mt-1 block w-full" wire:model.lazy="routine.name" required autofocus/>
                <x-input-error class="mt-2" :messages="$errors->get('routine.name')" />
            </div>

            <div>
                <x-input-label for="sm" :value="__('Bots types')" />
                <x-select-input id="sm" type="text" class="mt-1 block w-full" wire:model="routine.type" required>
                    <option value="passivbot">Passivbot</option>
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('routine.type')" />
            </div>
            <div>
                <x-input-label for="grid_mode" :value="__('Grid mode')" />
                <x-select-input id="grid_mode" type="text" class="mt-1 block w-full" wire:model="routine.action.grid_mode" required>
                    @foreach ($grid_modes as $mode_id => $mode_name)
                        <option value="{{$mode_id}}">{{$mode_name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('routine.action.grid_mode')" />
            </div>
            <div>
                <x-input-label for="grid_id" :value="__('Custom grid')" />
                <x-select-input id="grid_id" type="text" class="mt-1 block w-full" wire:model="routine.action.grid_id">
                    @foreach ($my_configs as $my_grid)
                        <option value="{{$my_grid->id}}">{{$my_grid->name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('routine.action.grid_id')" />
            </div>
        </div>

        <div class="grid grid-cols-4 grid-flow-col gap-4 mb-6">
            <div>
                <x-input-label for="lm" :value="__('Long mode (LM)')" />
                <x-select-input id="lm" type="text" class="mt-1 block w-full" wire:model="routine.action.lm" required>
                    @foreach ($bot_modes as $mode_id => $mode_name)
                        <option value="{{$mode_id}}">{{$mode_name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('routine.action.lm')" />
            </div>
            <div>
                <x-input-label for="sm" :value="__('Short mode (SM)')" />
                <x-select-input id="sm" type="text" class="mt-1 block w-full" wire:model="routine.action.sm" required>
                    @foreach ($bot_modes as $mode_id => $mode_name)
                        <option value="{{$mode_id}}">{{$mode_name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('routine.action.sm')" />
            </div>

            <div>
                <x-input-label for="lwe" :value="__('Long wallet exposure (LWE)')" />
                <x-text-input id="lwe" type="number" step="0.01" min="0" class="mt-1 block w-full" wire:model.lazy="routine.action.lwe" required/>
                <x-input-error class="mt-2" :messages="$errors->get('routine.action.lwe')" />
            </div>
            <div>
                <x-input-label for="swe" :value="__('Short wallet exposure (SWE)')" />
                <x-text-input id="swe" type="number" step="0.01" min="0" class="mt-1 block w-full" wire:model.lazy="routine.action.swe" required/>
                <x-input-error class="mt-2" :messages="$errors->get('routine.action.swe')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ isset($on_edit) ? __('Update Routine') : __('Create Routine') }}</x-primary-button>

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

    @isset($on_edit)
        <div class="mt-12 text-gray-900 dark:text-gray-100">
            <h2 class="text-lg font-medium">Webhook URL</h2>
            <div class="mt-4 bg-gray-300 dark:bg-gray-700 rounded-lg text-blue-800 dark:text-blue-400 rounded-lg border-blue-300 dark:border-blue-800 p-4 text-sm">
                <div class="flex justify-between">
                    {{ $routine->webhook_url }}
                    <a href="#copy" onclick="javascript:navigator.clipboard.writeText('{{ $routine->webhook_url }}')">
                        <svg class="float-right w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75"></path>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="params mt-4">
                <h4 class="mb-2 font-semibold text-gray-900 dark:text-white">Available parameters:</h4>
                <ul class="max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400">
                    <li>
                        end_scheduled_at: (timestamp)
                    </li>
                </ul>
            </div>
        </div>
    @endisset
</section>
