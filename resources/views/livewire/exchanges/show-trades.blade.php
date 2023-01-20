@php

@endphp
<x-secondary-header :title="__($title)" />
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="">
                <section>
                    <x-section-header
                        :h2text=" $chart_type == 'monthly' ? __('Monthly Profit & Loss') : __('Daily Profit & Loss')">
                        <div class="flex content-end">

                            <x-btn-link href="#refresh-table" title="Refresh" wire:click="$refresh" class="mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"></path>
                                </svg>
                            </x-btn-link>

                            <x-btn-link href="{{route('exchanges.positions', $exchange->id)}}" class=" mr-4">
                                <svg class="w-5 h-5 mr-1 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                                {{ __('Positions') }}
                            </x-btn-link>

                            <x-select-input id="exchange-select-id" type="text" class="w-40 mr-4" wire:model="exchange.id">
                                @foreach ($exchange->user->exchanges->pluck('name', 'id') as $exchange_id => $exchange_name)
                                    <option value="{{$exchange_id}}">{{$exchange_name}}</option>
                                @endforeach
                            </x-select-input>

                            <x-select-input id="chart_type" type="text" class="w-40 mr-4" wire:model="chart_type">
                                <option value="monthly">Trades per month</option>
                                <option value="daily">Trades per day</option>
                            </x-select-input>



                        </div>
                    </x-section-header>
                    <div class="mt-6 space-y-6">
                        @include('exchanges.trades-table')
                    </div>
                    {{-- <div class="mt-2">
                        {{ $records->links() }}
                    </div> --}}
                </section>
            </div>
        </div>
    </div>
</div>
