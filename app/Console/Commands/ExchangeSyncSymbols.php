<?php

namespace App\Console\Commands;

use App\Enums\ExchangesEnum;
use App\Models\Symbol;
use Illuminate\Console\Command;
use Ksoft\Bybit\BybitLinear;

class ExchangeSyncSymbols extends Command
{
    use Traits\RateLimitsTrait;

    protected $signature = 'antbot:sync-symbols';
    protected $description = 'Syncronize exchange symbols with local database';

    public function handle()
    {
        // logi('Starting SyncSymbols');
        $this->syncBybit();
        // logi('Ending SyncSymbols');
        return Command::SUCCESS;
    }

    protected function syncBybit()
    {
        $bybit = new BybitLinear();
        $ticks = $this->getBybitTicks($bybit);
        $results = $bybit->publics()->getSymbols();
        if ($results['ret_msg'] == 'OK'){
            $records = collect($results['result']);
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
        $results = $bybit->publics()->getTickers();
        if ($results['ret_msg'] == 'OK'){
            return collect($results['result']);
        } else {
            logi("Bybit syncBybitTicks Error:{$response['ret_msg']}");
            return [];
        }
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
