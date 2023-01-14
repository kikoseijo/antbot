<x-secondary-header :title="__($title)" />
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="">
                <section>
                    <div class="flex justify-center">

                        <div class="inline-flex rounded-md shadow-sm" role="group">
                          <button wire:click="refreshLog({{request()->fullUrl()}})" type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-l-lg hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"></path>
                              </svg>
                              Refresh
                          </button>
                          <button wire:click="truncateFile()" type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-r border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                            Truncate
                          </button>
                          <button wire:click="truncateAllFiles()" type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border-t border-b border-gray-200 rhover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                            Truncate All
                          </button>
                          <button wire:click="deleteLogsFile()" type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-r-md hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-2 focus:ring-blue-700 focus:text-blue-700 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-blue-500 dark:focus:text-white">
                              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"></path>
                              </svg>
                              Delete
                          </button>
                        </div>

                        <x-select-input wire:model="file" class="px-4 py-2 ml-5">
                            @foreach($files as $file)
                                <option value="{{ $loop->index }}">{{ $file->getFilename() }}</option>
                            @endforeach
                        </x-select-input>

                    </div>

                    <div class="text-center m-y-4 p-t-4 block h-8">
                        @include('partials.logs-paginator')
                    </div>

                    @if($log->count()>0)
                        <ul class="font-mono text-xs bg-[#0D0208] p-4 text-[#008F11] matrix-greens mt-6 overflow-y-scroll h-96">

                            @for($i=$log->count() - 1; $i > 0; $i--)
                                @if(Illuminate\Support\Str::startsWith($log[$i],'[stacktrace]') || Illuminate\Support\Str::startsWith($log[$i],'#'))
                                    <li x-data="{expanded:false}" x-on:click="expanded = !expanded">[stacktrace]
                                        <ul class="ml-8" x-show="expanded" x-cloak >
                                            @while($i < $log->count())
                                                <li wire:key="{{$page}}-line-{{ $i }}">{{ $log[$i] }}</li>
                                                @break(Illuminate\Support\Str::startsWith($log[$i++],'"}'))
                                            @endwhile
                                        </ul>
                                    </li>
                                @endif
                                @break($i>=$log->count())

                                <li wire:key="{{ $page }}-line-{{ $i }}" class="leading-5">
                                    {{ $log[$i] }}
                                </li>
                        @endfor
                    </ul>
                @endif
                </section>
            </div>
        </div>
    </div>
</div>
