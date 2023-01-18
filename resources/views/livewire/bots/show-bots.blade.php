<x-secondary-header :title="__($title)" />
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="">
                <section>
                    <x-section-header
                        h2text="{{ $sub_title }}"
                        :ptext="__('Here you can find a list of all your Antbots.')">
                        <div class="flex content-end">

                            <x-select-input id="exchange_selector" type="text" class="mr-4" wire:model="exchange.id">
                                @foreach ($exchanges as $exchange_id => $exchange_name)
                                    <option value="{{$exchange_id}}"{{ $exchange->id == $exchange_id ? ' selected' : ''}}>
                                        {{$exchange_name}}
                                    </option>
                                @endforeach
                            </x-select-input>

                            <x-btn-link href="{{route('exchanges.positions', $exchange)}}" class=" mr-4">
                                <svg class="w-5 h-5 mr-1 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                                {{ __('Positions') }}
                            </x-btn-link>

                            <x-btn-link href="{{route('exchanges.trades', $exchange)}}" class=" mr-4">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                {{ __('Trades') }}
                            </x-btn-link>

                            <x-btn-link href="{{ route('bots.add', $exchange) }}" class="py-1 px-4" title="Create new">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </x-btn-link>
                        </div>
                    </x-section-header>
                    <div class="mt-6 space-y-6">
                        @include('bots.table')
                        <div class="flex items-center text-center">
                            <a href="{{ route('bots.add', $exchange) }}" class='px-4 py-2 bg-yellow-300 border border-transparent rounded-md font-semibold text-md text-white uppercase tracking-widest hover:bg-yellow-500 active:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150'>
                                {{ __('Create new Bot') }}
                            </a>
                        </div>
                    </div>
                    <div class="mt-2">
                        {{ $records->links() }}
                    </div>
                </section>
            </div>
        </div>
    </div>
    @stack('show-bots-stack')
</div>
