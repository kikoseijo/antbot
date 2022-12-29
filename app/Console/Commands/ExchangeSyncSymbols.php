<?php

namespace App\Console\Commands;

use App\Enums\ExchangesEnum;
use App\Models\Symbol;
use Illuminate\Console\Command;
use Lin\Bybit\BybitLinear;

class ExchangeSyncSymbols extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'antbot:sync-symbols';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncronize exchange symbols with local database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->syncBybitSymbols();

        return Command::SUCCESS;
    }

    protected function syncBybitSymbols()
    {
        $bybit = new BybitLinear();
        $result=$bybit->publics()->getSymbols();
        if ($result['ret_msg'] == 'OK'){
            $records = collect($result['result']);
            foreach ($records as $record) {
                $normalized_record = $this->normalizeRecord($record);
                $new_record = Symbol::updateOrCreate([
                    'name' => $normalized_record['name'],
                    'exchange' => ExchangesEnum::BYBIT
                ],  $normalized_record);
            }
        }
    }

    protected function normalizeRecord($record)
    {
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

        return $record;
    }

    // protected function checkRateLimits($limit, $exchange_name)
    // {
    //     if ($limit < 50){
    //         \Log::info("Reaching exchange getSymbols limits {$exchange_name}LIMIT:{$limit}");
    //     }
    // }
}
