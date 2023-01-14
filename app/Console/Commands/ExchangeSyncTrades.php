<?php

namespace App\Console\Commands;

use App\Enums\ExchangesEnum;
use App\Models\Symbol;
use App\Models\Trade;
use App\Models\Exchange;
use Illuminate\Console\Command;
use Ksoft\Bybit\BybitLinear;

class ExchangeSyncTrades extends Command
{
    use Traits\RateLimitsTrait;

    protected $signature = 'antbot:sync-trades';
    protected $description = 'Syncronize exchange trade records.';

    public function handle()
    {
        // logi('Starting SyncTrades');
        $exchanges = Exchange::with('balances')->where('api_error', 0)->get();
        foreach ($exchanges as $exchange) {
            if ($exchange->exchange == ExchangesEnum::BYBIT) {
                $this->syncBybit($exchange);
            }
        }
        // logi('Ending SyncTrades');
        return Command::SUCCESS;
    }

    protected function syncBybit(Exchange $exchange)
    {
        $page = 1;
        $host = $exchange->is_testnet ? 'https://api-testnet.bybit.com' : 'https://api.bybit.com';
        $bybit = new BybitLinear($exchange->api_key, $exchange->api_secret, $host);
        $symbols = Symbol::where('exchange', $exchange->exchange)->get();

        foreach ($symbols as $symbol) {
            if ($exchange->api_error) {
                break;
            }
            $last_record = Trade::where('symbol', $symbol->name)
                ->where('exchange_id', $exchange->id)
                ->orderBy('created_at', 'desc')
                ->first();
            $start_time = $last_record->created_at->timestamp ?? now()->subYears(2)->timestamp;
            while ($page > 0) {
                $page = $this->syncBybitTradesForPage($page, $bybit, $exchange, $symbol->name, $start_time);
            }
            $page = 1;
        }
    }

    protected function syncBybitTradesForPage($page, $bybit, Exchange $exchange, $symbol, $start_time)
    {

        $response = $bybit->privates()->getTradeClosedPnlList([
            'symbol' => $symbol,
            'start_time' => $start_time,
            'limit' => 50,
            'page' => $page
        ]);
        if ($response['ret_msg'] == 'OK'){
            $filtered_response = collect($response['result']['data']);
            $page = $filtered_response->count() == 50 ? $page + 1 : 0;
            $this->saveExchangeTrades($exchange, $filtered_response);
            $this->checkRateLimits($response['rate_limit_status'], $exchange, 'syncTrades');
        } else {
            $page = 0;
            $this->processApiError($response['ret_msg'], $exchange, 'syncTrades');
        }

        return $page;
    }

    protected function saveExchangeTrades(Exchange $exchange, $filtered_response)
    {
        foreach ($filtered_response as $key => $data) {
            Trade::updateOrCreate([
                'position_id' => $data['id'],
                'exchange_id' => $exchange->id,
                'order_id' => $data['order_id']
            ],  \Arr::except($data, ['user_id']));
        }
    }
}
