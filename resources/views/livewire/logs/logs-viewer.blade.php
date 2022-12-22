<x-secondary-header :title="__('Logs')" />
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="">
                <section>
                    <div class="flex justify-around">
                        <select wire:model="file" class="px-4 py-2 font-mono text-sm bg-red-200 rounded">
                            @foreach($files as $file)
                                <option value="{{ $loop->index }}">{{ $file->getFilename() }}</option>
                            @endforeach
                        </select>
                    </div>

                    @include('partials.logs-paginator')

                    @if($log->count()>0)
                        <ul class='font-mono text-xs  mt-6'>

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

                                <li wire:key="{{ $page }}-line-{{ $i }}" class="font-mono text-xs leading-5 text-gray-600 dark:text-gray-400">{{ $log[$i] }}
                            </li>
                        @endfor
                    </ul>
                @endif
                </section>
            </div>
        </div>
    </div>
</div>
