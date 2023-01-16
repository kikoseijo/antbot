<x-secondary-header :title="__($title)" />
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="">
                <section>
                    <x-section-header
                        :h2text="__('Monthly PNL')">
                        <div class="flex content-end">
                            <x-select-input id="chart_type" type="text" class="mr-4 block w-full" wire:model="chart_type">
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
