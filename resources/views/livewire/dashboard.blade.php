<x-secondary-header :title="__($title)" />
<x-secondary-header :title="__($title)" />
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="">
                <section>
                    <x-section-header
                        :h2text="__('Exchanges')">
                        @if (auth()->user()->email != 'demo@sunnyface.com')
                            <x-btn-link href="{{ route('exchanges.add') }}">
                                <svg class="w-5 h-5 mr-1 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                {{ __('Create exchange') }}
                            </x-btn-link>
                        @endif
                    </x-section-header>
                    <div class="mt-6 space-y-6">
                        <div class="grid grid-cols-4 grid-flow-col gap-4 mb-6">
                            @forelse ($exchanges as $exchange)
                                @if ($loop->index % 4 == 0)
                                    </div>
                                    <div class="grid grid-cols-4 grid-flow-col gap-4 mb-6">
                                @endif
                                <div class="bg-gray-500 dark:bg-black/60 px-6 pt-6 rounded-lg" >
                                    <div class="flex flex-row space-x-4 items-center cursor-pointer" wire:click="showTrades('{{ $exchange->id }}')">
                                        <div id="stats-1">
                                            <div class="text-3xl p-4">💰</div>
                                        </div>
                                        <div>
                                            <p class="text-teal-300 text-sm font-medium uppercase leading-4">{{ $exchange->name }}</p>
                                            <p class="text-white font-bold text-2xl inline-flex items-center space-x-2">
                                                <span>{{ number($exchange->usdt_balance) }}</span>
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                                                      </svg>

                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="border-t border-white/5 py-4 flex items-left text-gray-200">
                                        <a href="#bots" wire:click="showBots('{{ $exchange->id }}')" class="inline-flex space-x-2 items-center text-center">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 17.25v-.228a4.5 4.5 0 00-.12-1.03l-2.268-9.64a3.375 3.375 0 00-3.285-2.602H7.923a3.375 3.375 0 00-3.285 2.602l-2.268 9.64a4.5 4.5 0 00-.12 1.03v.228m19.5 0a3 3 0 01-3 3H5.25a3 3 0 01-3-3m19.5 0a3 3 0 00-3-3H5.25a3 3 0 00-3 3m16.5 0h.008v.008h-.008v-.008zm-3 0h.008v.008h-.008v-.008z"></path>
                                            </svg>

                                             <span>{{ $exchange->bots_count }}&nbsp;Bots</span>
                                        </a>
                                        <a href="#positions" wire:click="showPositions('{{ $exchange->id }}')" class="inline-flex space-x-2 ml-4 items-center text-center">
                                              <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0"></path>
                                            </svg>
                                              <span>{{ $exchange->positions_count }}&nbsp;Positions</span>
                                        </a>

                                    </div>
                                </div>
                            @empty
                                <div class="col-span-4">
                                    <p class="p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white">
                                        <span class="whitespace-nowrap mb-4">You have 0 exchanges.</span>
                                        <br />
                                    </p>
                                </div>
                            @endforelse
                        </div>

                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
