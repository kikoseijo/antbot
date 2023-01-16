<?php

namespace App\Console\Commands;

use App\Enums\ExchangesEnum;
use App\Models\Balance;
use App\Models\Exchange;
use Illuminate\Console\Command;
use Ksoft\Bybit\BybitLinear;
use Ksoft\Bitget\BitgetSwap;

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
            } elseif ($exchange->exchange == ExchangesEnum::BITGET) {
                $this->syncBitget($exchange);
            }
        }
        // logi('Ending SyncBalance');

        return Command::SUCCESS;
    }

    protected function syncBitget(Exchange $exchange)
    {
        $client = new BitgetSwap($exchange->api_key, $exchange->api_secret, $exchange->api_frase);
        $response = $client->account()->getAccounts([
            'productType' => 'umcbl'
        ]);
        if ($response['msg'] == 'success'){
            $records = collect($response['data']);
            $symbols = $records->pluck('marginCoin');
            $this->removeNonExistingAssets($exchange, $symbols);
            $this->saveBitgetBalances($exchange, $records);
        } else {
            $this->processApiError($response['msg'], $exchange, 'SyncBalance');
        }
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
            $symbols = array_keys($filtered_response->toArray());
            $this->removeNonExistingAssets($exchange, $symbols);
            $this->saveBybitBalances($exchange, $filtered_response);
            $this->checkRateLimits($response['rate_limit_status'], $exchange, 'SyncBalance');
        } else {
            $this->processApiError($response['ret_msg'], $exchange, 'SyncBalance');
        }
    }

    protected function removeNonExistingAssets(Exchange $exchange, $symbols = [])
    {

        foreach ($exchange->balances as $exchange_balance) {
            if(!in_array($exchange_balance->symbol, $symbols)){
                $exchange_balance->delete();
            }
        }
    }

    protected function saveBybitBalances(Exchange $exchange, $records)
    {
        foreach ($records as $symbol => $data) {

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

    protected function saveBitgetBalances(Exchange $exchange, $records)
    {
        foreach ($records as $record) {

            $symbol = \Arr::get($record, 'marginCoin');
            $balance = $record['locked'] + $record['available'];
            $new_record = Balance::updateOrCreate([
                'symbol' => $symbol,
                'exchange_id' => $exchange->id
            ], [
                'wallet_balance' => $balance,
                'equity' => \Arr::get($record, 'equity'),
                'available_balance' => \Arr::get($record, 'available'),
                'used_margin' => \Arr::get($record, 'locked'),
                'order_margin' => \Arr::get($record, 'locked'),
                // 'position_margin' => \Arr::get($record, 'XXXXXXXX'),
                // 'occ_closing_fee' => \Arr::get($record, 'XXXXXXXX'),
                // 'occ_funding_fee' => \Arr::get($record, 'XXXXXXXX'),
                // 'realised_pnl' => \Arr::get($record, 'XXXXXXXX'),
                // 'unrealised_pnl' => \Arr::get($record, 'XXXXXXXX'),
                // 'cum_realised_pnl' => \Arr::get($record, 'XXXXXXXX'),
            ]);

            if (in_array($symbol, ['USDT', 'USD', 'BTC', 'ETH'])) {
                $column_name = \Str::lower($symbol) . '_balance';
                $exchange->$column_name = $balance;
                $exchange->save();
            }
        }

    }

}
