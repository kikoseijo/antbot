
<div class="overflow-x-auto relative sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-6">#</th>
                <th scope="col" class="py-3 px-6">Name</th>
                <th scope="col" class="py-3 px-6">Description</th>
                <th scope="col" class="py-3 px-6">Created</th>
                <th scope="col" class="py-3 px-6">Updated</th>
                <th scope="col" class="py-3 px-6"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $record)
                <tr class="bg-white {{ $loop->index % 2 == 0 ? 'bg-gray-100' : ''}} dark:bg-gray-900{{ $loop->last ? '' : ' border-b dark:border-gray-400'}} hover:bg-teal-100 hover:dark:bg-teal-500 hover:dark:text-white">
                    <th scope="row" class="py-2 px-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $record->id }}
                    </th>
                    <td class="py-2 px-4 font-bold {{ $record->bots->count() > 0 ? 'text-yellow-300 decoration-yellow-300' : ''}} underline hover:no-underline">
                        <a href="{{ route('configs.edit', $record) }}">
                            <span class="uppercase ">{{ $record->name }}</span>
                        </a>
                    </td>
                    <td class="py-2 px-4 truncate">
                        <a href="{{ route('configs.edit', $record) }}">
                            <span class="ml-3 italic text-sm">{{ $record->description }}</span>
                        </a>
                    </td>
                    <td class="py-2 px-4">
                        {{ $record->created_at->format('d-m-Y H:i') }}
                    </td>
                    <td class="py-2 px-4">
                        {{ $record->updated_at->format('d-m-Y H:i') }}
                    </td>
                    <td class="py-2 px-4 text-right">
                        <div class="inline-flex rounded-md shadow-sm" role="group">
                          <a href="{{route('configs.edit', $record)}}" title="Edit" type="button" class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                          </a>
                          <a href="{{ route('configs.visual-edit', $record) }}" title="Visual editor" class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-900 bg-white border-t border-b border-r border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                          </a>
                          <button x-data="" wire:click="deleteId({{ $record->id }})" title="Delete" x-on:click.prevent="$dispatch('open-modal', 'confirm-config-deletion')" type="button" class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-900 bg-red-600 hover:bg-red-500 active:bg-red-700 border border-gray-200 rounded-r-md hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:focus:ring-blue-500 dark:focus:text-white">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                          </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="text-center bg-white dark:bg-gray-900">
                    <td colspan="10" class="py-4 px-6 italic">No hay información</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


<x-modal name="confirm-config-deletion" focusable>
    <div class="p-4">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Are you sure your want to delete the Grid Configuration?</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('This action can NOT be undone.') }}
        </p>
        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-danger-button class="ml-3" wire:click.prevent="destroy()" x-on:click="$dispatch('close')">
                {{ __('Delete Config') }}
            </x-danger-button>
        </div>
    </div>
</x-modal>
