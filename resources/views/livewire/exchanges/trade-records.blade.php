<x-secondary-header :title="__($title)" />
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="">
                <section>
                    <x-section-header
                        :h2text=" __('Trade Records Profit & Loss')">
                        <div class="flex content-end">
                            <x-btn-link href="#refresh-table" title="Refresh" wire:click="$refresh" class="mr-4">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"></path>
                                </svg>
                            </x-btn-link>
                            <!-- Date range filter -->
                            <x-date-range class="mr-2"></x-date-range>
                            <x-select-input id="symbol-select-id" type="text" class="w-48 mr-4" wire:model="select_symbol">
                                @foreach ($symbols as $symbols_id => $symbol_name)
                                    <option value="{{ $symbol_name }}">{{ $symbol_name }}</option>
                                @endforeach
                            </x-select-input>
                            <x-select-input id="n-records" type="text" class="w-24 mr-4" wire:model="n_records">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="500">500</option>
                            </x-select-input>
                        </div>
                    </x-section-header>
                    <div class="mt-6 space-y-6">
                        @include('exchanges.trade-records')
                    </div>
                    <div class="mt-2">
                        {{ $records->links() }}
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.3/datepicker.min.js"></script>
@endpush
