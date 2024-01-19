
<div class="overflow-x-auto relative sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-2 text-center">Name</th>
                <th scope="col" class="py-3 px-2 text-center">Type</th>
                <th scope="col" class="py-3 px-2 text-center">Grid</th>
                <th scope="col" class="py-3 px-2 text-center">Long mode</th>
                <th scope="col" class="py-3 px-2 text-center">Short mode</th>
                <th scope="col" class="py-3 px-2 text-center">Last ejecution</th>
                <th scope="col" class="py-3 px-2 text-center"></th>
            </tr>
        </thead>
        <tbody>
            @php
                $bot_modes = config('antbot.bot_modes');
                $routines_types = config('antbot.routines_types');
            @endphp
            @forelse($records as $record)
                @php
                    $act = (object) $record->action;
                    $long_mode = '<b>'.\Arr::get($bot_modes, $act->lm) . '</b>&nbsp;<b>' . $act->lwe .'</b>';
                    $short_mode = '<b>'.\Arr::get($bot_modes, $act->sm) . '</b>&nbsp;<b>' . $act->swe .'</b>';
                @endphp
                <tr class="bg-white {{ $loop->index % 2 == 0 ? 'bg-gray-100' : ''}} dark:bg-gray-900{{ $loop->last ? '' : ' border-b dark:border-gray-400'}} hover:bg-teal-100 hover:dark:bg-[#080C19] hover:dark:text-white">

                    <td class="py-2 px-2 font-bold no-underline hover:underline">
                        <a href="{{ route('routines.edit', $record) }}">
                            {{ $record->name }}
                        </a>
                    </td>
                    <td class="py-2 px-2">
                        {{ \Arr::get($routines_types, $record->type) }}
                    </td>
                    <td class="py-2 px-2">
                        @if ($act->grid_mode == 'custom' && $act->grid_id > 0)
                            <a href="{{ route('configs.edit', $act->grid_id) }}" class="no-underline hover:underline">
                                {{ \App\Models\Grid::find($act->grid_id)->name }}
                            </a>
                        @else
                            {{ $act->grid_mode }}
                        @endif
                        <br />
                    </td>
                    <td class="py-2 px-2 text-center">
                        {!! $long_mode !!}
                    </td>
                    <td class="py-2 px-2 text-center">
                        {!! $short_mode !!}
                    </td>
                    <td class="py-2 px-2 font-bold text-center">
                        {{ optional($record->triggered_at)->diffForHumans(NULL, true) }}
                    </td>
                    <td class="py-2 px-2 text-right">
                        <div class="inline-flex rounded-md shadow-sm" role="group">
                          <button x-data="" wire:click="runId('{{ $record->id }}')" title="Run routine" x-on:click.prevent="$dispatch('open-modal', 'confirm-routine-execution')" type="button" class="inline-flex items-center px-2 py-2 text-xs font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z"></path>
                              </svg>
                          </button>
                          <a href="{{ route('routines.edit', $record) }}" title="Edit" class="inline-flex items-center px-2 py-2 text-xs font-medium text-gray-900 bg-white border-t border-b border-gray-200 rhover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                          </a>
                          <a href="#duplicate" wire:click="duplicateRoutine('{{ $record->id }}')" title="Duplicate" class="inline-flex items-center px-2 py-1 text-xs font-medium text-gray-900 bg-white border-t border-b border-r border-gray-200 rhover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 8.25V6a2.25 2.25 0 00-2.25-2.25H6A2.25 2.25 0 003.75 6v8.25A2.25 2.25 0 006 16.5h2.25m8.25-8.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-7.5A2.25 2.25 0 018.25 18v-1.5m8.25-8.25h-6a2.25 2.25 0 00-2.25 2.25v6"></path>
                              </svg>
                          </a>
                          <button x-data="" wire:click="deleteId('{{ $record->id }}')" title="Delete" x-on:click.prevent="$dispatch('open-modal', 'confirm-routine-deletion')" type="button" class="inline-flex items-center px-2 py-2 text-xs font-medium text-gray-900 bg-red-600 hover:bg-red-500 active:bg-red-700 border border-gray-200 rounded-r-md hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:focus:ring-blue-500 dark:focus:text-white">
                              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                          </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr class="text-center bg-white dark:bg-gray-900">
                    <td colspan="7" class="py-4 px-2 italic">No routines found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


<x-modal name="confirm-routine-execution" focusable>
    <div class="p-4">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Are you sure your want to execute the routine NOW?</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('This action can NOT be undone.') }}
        </p>
        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-danger-button class="ml-3" wire:click.prevent="runRoutine()" x-on:click="$dispatch('close')">
                {{ __('Run') }}
            </x-danger-button>
        </div>
    </div>
</x-modal>

<x-modal name="confirm-routine-deletion" focusable>
    <div class="p-4">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Are you sure your want to delete the routine?</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('This action can NOT be undone.') }}
        </p>
        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-danger-button class="ml-3" wire:click.prevent="destroy()" x-on:click="$dispatch('close')">
                {{ __('Delete') }}
            </x-danger-button>
        </div>
    </div>
</x-modal>
