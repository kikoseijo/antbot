<div class="inline-block w-96 max-w-xs sm:w-max overflow-hidden mr-3 px-6 pt-6 bg-white border border-gray-200 rounded-lg shadow-xs dark:bg-gray-800 dark:border-gray-700">
    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $balance->symbol }}</h5>
    <dl class="grid max-w-full grid-cols-2 gap-2 mx-auto text-gray-900 sm:grid-cols-3 xl:grid-cols-3 dark:text-white">
        <div class="flex flex-col">
            <dt class="mb-2 text-md font-extrabold truncate">$ {{ number($balance->wallet_balance)}}</dt>
            <dd class="font-light text-sm text-gray-500 dark:text-gray-400">Balanace</dd>
        </div>
        <div class="flex flex-col">
            <dt class="mb-2 text-md font-extrabold truncate {{ $balance->unrealised_pnl < 0 ? 'text-red-500 dark:text-red-400' : 'text-green-500 dark:text-green-400'}}">$ {{ number($balance->unrealised_pnl)}}</dt>
            <dd class="font-light text-sm text-gray-500 dark:text-gray-400">Unrealised PNL</dd>
        </div>
        <div class="flex flex-col">
            <dt class="mb-2 text-md font-extrabold truncate {{ $balance->realised_pnl < 0 ? 'text-red-500 dark:text-red-400' : 'text-green-500 dark:text-green-400'}}">$ {{ number($balance->realised_pnl)}}</dt>
            <dd class="font-light text-sm text-gray-500 dark:text-gray-400">Realised PNL</dd>
        </div>
    </dl>
    <div id="accordion-collapse" data-accordion="collapse">
        <h2 id="accordion-collapse-heading-{{$balance->id}}">
            <button type="button" class="flex items-center justify-between w-full p-5 font-medium text-left text-gray-500 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800" data-accordion-target="#accordion-collapse-body-{{$balance->id}}" aria-expanded="false" aria-controls="accordion-collapse-body-{{$balance->id}}">
                <span>More info</span>
                <svg data-accordion-icon class="w-6 h-6 rotate-180 shrink-0" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </button>
        </h2>
        <div id="accordion-collapse-body-{{$balance->id}}" class="hidden" aria-labelledby="accordion-collapse-heading-{{$balance->id}}">
            <div class="p-5 font-light border border-gray-200 dark:border-gray-700 dark:bg-gray-900">
                <ul role="list" class="space-y-4 text-gray-500 dark:text-gray-400">

                    @php
                        $twe = $total_wallet_exposure[$balance->symbol];
                        $gauge_percentage = ($twe * 100) / 400;
                    @endphp
                    <label for="disk_d">Total Wallet Exposure:</label>
                    <meter id="disk_d" value="{{$gauge_percentage}}">{{$gauge_percentage}}%</meter>
                    @include('exchanges.partials.list-info',['title' => 'TWE', 'amount' => $twe * 100])

                    @include('exchanges.partials.list-info',['title' => 'equity', 'amount' => $balance->equity])
                    @include('exchanges.partials.list-info',['title' => 'available_balance', 'amount' => $balance->available_balance])
                    @include('exchanges.partials.list-info',['title' => 'used_margin', 'amount' => $balance->used_margin])
                    @include('exchanges.partials.list-info',['title' => 'order_margin', 'amount' => $balance->order_margin])
                    @include('exchanges.partials.list-info',['title' => 'position_margin', 'amount' => $balance->position_margin])
                    @include('exchanges.partials.list-info',['title' => 'occ_closing_fee', 'amount' => $balance->occ_closing_fee])
                    @include('exchanges.partials.list-info',['title' => 'occ_funding_fee', 'amount' => $balance->occ_funding_fee])
                    @include('exchanges.partials.list-info',['title' => 'cum_realised_pnl', 'amount' => $balance->cum_realised_pnl])
                </ul>
            </div>
        </div>
    </div>

</div>
