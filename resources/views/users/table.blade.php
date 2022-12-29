
<div class="overflow-x-auto relative rounded-t-xl">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-6">#</th>
                <th scope="col" class="py-3 px-6">Name</th>
                <th scope="col" class="py-3 px-6">Email</th>
                <th scope="col" class="py-3 px-6">Last seen</th>
                <th scope="col" class="py-3 px-6"></th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $user)
                <tr class="bg-white dark:bg-gray-900{{ $loop->last ? '' : ' border-b dark:border-gray-400'}}">
                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $user->id }}
                    </th>
                    <td class="py-4 px-6 font-bold underline hover:no-underline">
                        <a href="{{ route('users.edit', $user) }}">
                            {{ $user->name }}
                        </a>
                    </td>
                    <td class="py-4 px-6">
                        {{ $user->email }}
                    </td>
                    <td class="py-4 px-6">
                        {{ \Carbon\Carbon::parse($user->last_seen)->diffForHumans() }}
                    </td>
                    <td class="py-4 px-6 text-right">
                        <x-btn-link class="py-1 px-2 mr-2" href="{{ route('users.edit', $user) }}" >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </x-btn-link>
                        <x-danger-button class="py-1 px-2"
                            wire:click="deleteId({{ $user->id }})"
                            x-data=""
                            x-on:click.prevent="$dispatch('open-modal', 'confirm-config-deletion')"
                        >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </x-danger-button>
                        {{-- <a href="/user/edit/{{ $user->id }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                        <a href="/user/edit/{{ $user->id }}" class="text-red-600 hover:text-red-900">Delete</a> --}}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
