<x-secondary-header :title="__($title)" />
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="grid grid-cols-3 gap-4">
            @foreach ($balances as $balance)
                @include('exchanges.balance-card', ['balance' => $balance])
            @endforeach
        </div>
        <div class="mt-8 space-y-6">
            <livewire:exchanges.positions-table :exchange="$exchange" />
        </div>
    </div>
