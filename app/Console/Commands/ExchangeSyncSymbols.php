<?php

namespace App\Console\Commands;

use App\Enums\ExchangesEnum;
use App\Models\Symbol;
use App\Models\Exchange;
use Illuminate\Console\Command;
use Ksoft\Bybit\BybitLinear;
use Ksoft\Bitget\BitgetSwap;

class ExchangeSyncSymbols extends Command
{
    use Traits\RateLimitsTrait;

    protected $signature = 'antbot:sync-symbols';
    protected $description = 'Syncronize exchange symbols with local database';

    public function handle()
    {
        // logi('Starting SyncSymbols');
        $this->syncBybit();
        $this->syncBitget();
        // logi('Ending SyncSymbols');

        return Command::SUCCESS;
    }

    protected function syncBitget()
    {
        $client = new BitgetSwap();
        $ticks = $this->getBitgetTicks($client);
        $response = $client->market()->getContracts([
            'productType' => 'umcbl'
        ]);
        $api_limit = 0;
        if ($response['msg'] == 'success'){
            $records = collect($response['data']);
            foreach ($records as $record) {
                $symbol_name = \Arr::get($record, 'symbol');
                $normalized_record = $this->normalizeBitgetRecord(
                    $record,
                    $ticks->where('symbol', $symbol_name)->values(),
                    $client
                );
                $new_record = Symbol::updateOrCreate([
                    'name' => $normalized_record['name'],
                    'exchange' => ExchangesEnum::BITGET
                ],  $normalized_record);

                // Limit rule: 20 times/1s (IP)
                if($api_limit % 15 == 0){
                    sleep(1);
                }
                $api_limit++;
            }
        } else {
            logi("Bitget syncSymbols Error:{$response['msg']}");
        }
    }

    // https://bitgetlimited.github.io/apidoc/en/mix/#producttype
    // umcbl USDT perpetual contract
    // dmcbl Universal margin perpetual contract
    // cmcbl USDC perpetual contract
    // sumcbl USDT simulation perpetual contract
    // sdmcbl Universal margin simulation perpetual contract
    // scmcbl USDC simulation perpetual contract
    protected function getBitgetTicks($client)
    {
        $response = $client->market()->getTickers([
            'productType' => 'umcbl'
        ]);
        if ($response['msg'] == 'success'){
            return collect($response['data']);
        } else {
            logi("Bitget getTicks Error:{$response['msg']}");
            return [];
        }
    }

    protected function syncBybit()
    {
        $bybit = new BybitLinear();
        $ticks = $this->getBybitTicks($bybit);
        $response = $bybit->publics()->getSymbols();
        if ($response['ret_msg'] == 'OK'){
            $records = collect($response['result']);
            foreach ($records as $record) {
                $symbol_name = \Arr::get($record, 'name');
                $normalized_record = $this->normalizeBybitRecord($record, $ticks->where('symbol', $symbol_name)->values()[0]);
                $new_record = Symbol::updateOrCreate([
                    'name' => $normalized_record['name'],
                    'exchange' => ExchangesEnum::BYBIT
                ],  $normalized_record);
            }
        } else {
            logi("Bybit syncSymbols Error:{$response['ret_msg']}");
        }
    }

    protected function getBybitTicks($bybit)
    {
        $response = $bybit->publics()->getTickers();
        if ($response['ret_msg'] == 'OK'){
            return collect($response['result']);
        } else {
            logi("Bybit syncTicks Error:{$response['ret_msg']}");
            return [];
        }
    }

    protected function normalizeBitgetRecord($record, $tick, $client)
    {
        // $record['market'] = 'Futures';
        $record['name'] = \Arr::get($record, 'symbol');

        unset( $record['supportMarginCoins'] );

        $record['base_currency'] = \Arr::get($record, 'baseCoin');
        $record['quote_currency'] = \Arr::get($record, 'quoteCoin');
        $record['maker_fee'] = \Arr::get($record, 'makerFeeRate');
        $record['taker_fee'] = \Arr::get($record, 'takerFeeRate');
        $record['min_trading_qty'] = \Arr::get($record, 'minTradeNum');
        $record['price_scale'] = \Arr::get($record, 'pricePlace');
        $record['market'] = \Arr::get($record, 'symbolType');
        $record['status'] = \Arr::get($record, 'symbolStatus');
        // $record['XXXXXXX'] = \Arr::get($record, 'XXXXXX');

        $record['last_price'] = \Arr::get($tick, 'last');
        $record['mark_price'] = \Arr::get($tick, 'last');
        $record['high_price_24h'] = \Arr::get($tick, 'high24h');
        $record['low_price_24h'] = \Arr::get($tick, 'low24h');
        $record['price_24h_pcnt'] = \Arr::get($tick, 'priceChangePercent');
        $record['volume_24h'] = \Arr::get($tick, 'baseVolume');
        // $record['XXXXXXX'] = \Arr::get($tick, 'quoteVolume');
        // $record['XXXXXXX'] = \Arr::get($tick, 'usdtVolume');

        $res = $client->market()->getSymbolLeverage([
            'symbol' => $record['name']
        ]);
        $record['min_leverage'] = \Arr::get($res, 'data.minLeverage');
        $record['max_leverage'] = \Arr::get($res, 'data.maxLeverage');

        return $record;
    }

    protected function normalizeBybitRecord($record, $record_tick)
    {
        $record['market'] = 'Futures';

        $record['min_leverage'] = \Arr::get($record, 'leverage_filter.min_leverage');
        $record['max_leverage'] = \Arr::get($record, 'leverage_filter.max_leverage');
        $record['leverage_step'] = \Arr::get($record, 'leverage_filter.leverage_step');
        unset( $record['leverage_filter'] );

        $record['min_price'] = \Arr::get($record, 'price_filter.min_price');
        $record['max_price'] = \Arr::get($record, 'price_filter.max_price');
        $record['tick_size'] = \Arr::get($record, 'price_filter.tick_size');
        unset( $record['price_filter'] );

        $record['max_trading_qty'] = \Arr::get($record, 'lot_size_filter.max_trading_qty');
        $record['min_trading_qty'] = \Arr::get($record, 'lot_size_filter.min_trading_qty');
        $record['qty_step'] = \Arr::get($record, 'lot_size_filter.qty_step');
        $record['post_only_max_trading_qty'] = \Arr::get($record, 'lot_size_filter.post_only_max_trading_qty');
        unset( $record['lot_size_filter'] );

        $record = array_merge((array) $record, \Arr::only((array) $record_tick, [
            'last_price', 'prev_price_24h', 'price_24h_pcnt',
            'high_price_24h', 'low_price_24h', 'prev_price_1h',
            'mark_price', 'index_price', 'turnover_24h', 'volume_24h'
        ]));

        return $record;
    }

}
