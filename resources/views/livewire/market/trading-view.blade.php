<x-secondary-header :title="__($title)" />
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
            <div class="mb-4">
                @include('markets.trading-view')
            </div>

            <x-btn-link-accent class="py-4 mt-4" href="{{ route('exchanges.positions', $position->exchange) }}" >
                Back to positions
            </x-btn-link-accent>
        </div>
    </div>
</div>
