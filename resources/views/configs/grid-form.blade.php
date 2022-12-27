<form wire:submit.prevent="submit" class="mt-6 space-y-6">
    <div class="grid grid-cols-8 grid-flow-col gap-6">
        <div class="col-span-2">
            <div class="mb-3">
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" type="text" class="mt-1 block w-full uppercase" wire:model.defer="grid.name" required autofocus/>
                <x-input-error class="mt-2" :messages="$errors->get('grid.name')" />
            </div>
            <div class="mb-3">
                <x-input-label for="config_name" :value="__('Config name')" />
                <x-text-input id="config_name" type="text" class="mt-1 block w-full" wire:model.defer="common.config_name" required/>
                <x-input-error class="mt-2" :messages="$errors->get('common.config_name')" />
            </div>
            <div class="mb-3">
                <x-input-label for="logging_level" :value="__('Loggin level')" />
                <x-text-input id="logging_level" type="number" class="mt-1 block w-full" wire:model.defer="common.logging_level" required/>
                <x-input-error class="mt-2" :messages="$errors->get('common.logging_level')" />
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
        <div class="col-span-6" wire:ignore>
            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Chart preview</h4>
            <div id="tv-grid-edit" symbol="{{ $grid->name }}" style="width:100%; height:450px;"></div>

            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="gridEditTab" data-tabs-toggle="#gridEditTabContent" role="tablist">
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 rounded-t-lg border-b-2" id="long-tab" data-tabs-target="#longs" type="button" role="tab" aria-controls="longs" aria-selected="false">Long mode</button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 rounded-t-lg border-b-2 border-transparent hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" id="short-tab" data-tabs-target="#shorts" type="button" role="tab" aria-controls="shorts" aria-selected="false">Short mode</button>
                    </li>

                </ul>
            </div>
            <div id="gridEditTabContent">
                <div class="hidden p-4 bg-gray-50 rounded-lg dark:bg-gray-800" id="longs" role="tabpanel" aria-labelledby="long-tab">
                    @include('configs.grid-fields', ['grid_mode' => 'l_grid'])
                </div>
                <div class="hidden p-4 bg-gray-50 rounded-lg dark:bg-gray-800" id="shorts" role="tabpanel" aria-labelledby="short-tab">
                    @include('configs.grid-fields', ['grid_mode' => 's_grid'])
                </div>

            </div>

            <div class="">
            </div>

        </div>
    </div>






    <div class="flex items-center gap-4">
        <x-primary-button wire:click="submit">{{ __('Save grid') }}</x-primary-button>
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
