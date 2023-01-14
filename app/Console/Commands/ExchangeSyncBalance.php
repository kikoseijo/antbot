<?php

namespace App\Console\Commands;

use App\Enums\ExchangesEnum;
use App\Models\Balance;
use App\Models\Exchange;
use Illuminate\Console\Command;
use Ksoft\Bybit\BybitLinear;

class ExchangeSyncBalance extends Command
{
    use Traits\RateLimitsTrait;

    protected $signature = 'antbot:sync-balance';
    protected $description = 'Sync exchanges account balance.';

    public function handle()
    {
        // logi('Starting SyncBalance');
        $exchanges = Exchange::with('balances')->where('api_error', false)->get();
        foreach ($exchanges as $exchange) {
            if ($exchange->exchange == ExchangesEnum::BYBIT) {
                $this->syncBybit($exchange);
            }
        }
        // logi('Ending SyncBalance');
        return Command::SUCCESS;
    }

    protected function syncBybit(Exchange $exchange)
    {
        $host = $exchange->is_testnet ? 'https://api-testnet.bybit.com' : 'https://api.bybit.com';
        $bybit = new BybitLinear($exchange->api_key, $exchange->api_secret, $host);
        $response = $bybit->privates()->getWalletBalance();
        if ($response['ret_msg'] == 'OK'){
            $filtered_response = collect($response['result'])->filter(function ($value, $key) {
                return $value['wallet_balance'] > 0;
            });
            $this->removeNonExistingAssets($exchange, $filtered_response);
            $this->saveExchangeBalances($exchange, $filtered_response);
            $this->checkRateLimits($response['rate_limit_status'], $exchange, 'SyncBalance');
        } else {
            $this->processApiError($response['ret_msg'], $exchange, 'SyncBalance');
        }
    }

    protected function removeNonExistingAssets(Exchange $exchange, $filtered_response)
    {
        $symbols = array_keys($filtered_response->toArray());
        foreach ($exchange->balances as $exchange_balance) {
            if(!in_array($exchange_balance->symbol, $symbols)){
                $exchange_balance->delete();
            }
        }
    }

    protected function saveExchangeBalances(Exchange $exchange, $filtered_response)
    {
        foreach ($filtered_response as $symbol => $data) {

            $new_record = Balance::updateOrCreate([
                'symbol' => $symbol,
                'exchange_id' => $exchange->id
            ],  \Arr::except($data, ['given_cash', 'service_cash']));

            if (in_array($symbol, ['USDT', 'USD', 'BTC', 'ETH'])) {
                $column_name = \Str::lower($symbol) . '_balance';
                $exchange->$column_name = $data['wallet_balance'];
                $exchange->save();
            }
        }

    }

}
