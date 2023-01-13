<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ isset($on_edit) ? __('Edit user') : __('Create new user') }}
        </h2>
    </header>

    <form wire:submit.prevent="submit" class="mt-6 space-y-6">

        <div class="grid grid-cols-3 grid-flow-col gap-4">
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" type="text" class="mt-1 block w-full uppercase" wire:model.defer="user.name" required autofocus/>
                <x-input-error class="mt-2" :messages="$errors->get('user.name')" />
            </div>
            <div class="">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" type="text" class="mt-1 block w-full" wire:model.defer="user.email"/>
                <x-input-error class="mt-2" :messages="$errors->get('user.email')" />
            </div>
        </div>

        <div class="grid grid-cols-3 grid-flow-col gap-4">
            <div>
                <x-input-label for="role" :value="__('User role')" />
                <x-select-input id="role" type="text" class="mt-1 block w-full" wire:model="user.role" required>
                    @foreach ($user_roles as $role_id => $role_name)
                        <option value="{{$role_id}}">{{$role_name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('user.role')" />
            </div>
            <div>
                <x-input-label for="timezone" :value="__('User timezone')" />
                <x-select-input id="timezone" type="text" class="mt-1 block w-full" wire:model="user.timezone" required>
                    @foreach ($timezones as $timezone_id => $timezone_name)
                        <option value="{{$timezone_name}}">{{$timezone_name}}</option>
                    @endforeach
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('user.role')" />
            </div>

        </div>
        <div class="grid grid-cols-3 grid-flow-col gap-4">
            <div>
                <x-input-label for="admin" :value="__('Super Admin')" />
                <div class="flex mt-2">
                    <div class="flex items-center mr-4">
                        <input id="admin_1" type="radio" value="1"  wire:model="user.admin" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="admin_1" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Yes</label>
                    </div>
                    <div class="flex items-center mr-4 ml-4">
                        <input id="admin_2" type="radio" value="0"  wire:model="user.admin" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                        <label for="admin_2" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">No</label>
                    </div>
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('user.admin')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button wire:click="submit">{{ isset($on_edit) ? __('Update user') : __('Create new user') }}</x-primary-button>

            @if (session('status') === 'user-created' || session('status') === 'user-updated')
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
