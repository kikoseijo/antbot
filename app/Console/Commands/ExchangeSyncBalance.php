<?php

namespace App\Console\Commands;

use App\Enums\ExchangesEnum;
use App\Models\Balance;
use App\Models\Exchange;
use Illuminate\Console\Command;
use Ksoft\Bybit\BybitLinear;

class ExchangeSyncBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'antbot:sync-balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync exchanges account balance.';

    /**
     * Execute the console command.
     *
     * @return int
     */
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
        $bybit = new BybitLinear($exchange->api_key, $exchange->api_secret);
        $response = $bybit->privates()->getWalletBalance();
        $this->checkRateLimits($response['rate_limit_status'], $exchange);
        if ($response['ret_msg'] == 'OK'){
            $filtered_response = collect($response['result'])->filter(function ($value, $key) {
                return $value['wallet_balance'] > 0;
            });
            $this->removeNonExistingAssets($exchange, $filtered_response);
            $this->saveExchangeBalances($exchange, $filtered_response);
        } else {
            if (in_array($response['ret_msg'], ['invalid api_key', 'API key is invalid.'])) {
                $exchange->api_error = 1;
                $exchange->save();
            }
            \Log::info("Bybit balance sync {$exchange->name} #{$exchange->id} Error:{$response['ret_msg']}");
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

    protected function checkRateLimits($limit, Exchange $exchange)
    {
        if ($limit < 30){
            sleep(3);
        }
        if ($limit < 10){
            \Log::info("Reaching exchange SyncBalance limits {$exchange->name} #{$exchange->id} LIMIT:{$limit}");
        }
    }
}
