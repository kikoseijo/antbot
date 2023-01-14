
<div class="overflow-x-auto relative sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-2">#</th>
                <th scope="col" class="py-3 px-2">Role</th>
                <th scope="col" class="py-3 px-2">Name</th>
                <th scope="col" class="py-3 px-2">Email</th>
                <th scope="col" class="py-3 px-2">Timezone</th>
                <th scope="col" class="py-3 px-2">Exch.</th>
                <th scope="col" class="py-3 px-2">Bots</th>
                <th scope="col" class="py-3 px-2">Grids</th>
                <th scope="col" class="py-3 px-2">Last seen</th>
                <th scope="col" class="py-3 px-2"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $user)
                <tr class="bg-white dark:bg-gray-900{{ $loop->last ? '' : ' border-b dark:border-gray-400'}} hover:bg-gray-100 hover:dark:bg-teal-500 hover:dark:text-white">
                    <th scope="row" class="py-2 px-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $user->id }}
                    </th>
                    <td class="py-2 px-2">
                        {{ \Arr::get($user_roles, $user->role) }}
                    </td>
                    <td class="py-2 px-2 font-bold underline hover:no-underline">
                        <a href="{{ route('users.edit', $user) }}">
                            {{ $user->name }}
                        </a>
                    </td>
                    <td class="py-2 px-2">
                        {{ $user->email }}
                    </td>
                    <td class="py-2 px-2">
                        {{ $user->timezone }}
                    </td>
                    <td class="py-2 px-2 text-center">
                        {{ $user->exchanges_count }}
                    </td>
                    <td class="py-2 px-2 text-center">
                        {{ $user->bots_count }}
                    </td>
                    <td class="py-2 px-2 text-center">
                        {{ $user->grids_count }}
                    </td>
                    <td class="py-2 px-2">
                        {{ \Carbon\Carbon::parse($user->last_seen)->diffForHumans() }}
                    </td>
                    <td class="py-2 px-2 text-right">

                        <div class="inline-flex rounded-md shadow-sm" role="group">
                          <a href="{{route('users.edit', $user)}}" title="Edit" type="button" class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                          </a>
                          @canImpersonate
                              @canBeImpersonated($user)
                                  <a href="{{ route('impersonate', $user) }}" title="Impersonate" class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-900 bg-white border-t border-b border-r border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                                      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                      </svg>
                                  </a>
                              @endCanBeImpersonated
                          @endCanImpersonate
                          <button x-data="" wire:click="deleteId({{ $user->id }})" title="Delete" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" type="button" class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-900 bg-red-600 hover:bg-red-500 active:bg-red-700 border border-gray-200 rounded-r-md hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:focus:ring-blue-500 dark:focus:text-white">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                          </button>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<x-modal name="confirm-user-deletion" focusable>
    <div class="p-4">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Are you sure your want to delete the user & all associated data?</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('This action can NOT be undone.') }}
        </p>
        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-danger-button class="ml-3" wire:click.prevent="destroy()" x-on:click="$dispatch('close')">
                {{ __('Delete user') }}
            </x-danger-button>
        </div>
    </div>
</x-modal>
