<?php

namespace App\Console\Commands;

use App\Enums\ExchangesEnum;
use App\Models\Order;
use App\Models\Position;
use Illuminate\Console\Command;
use Ksoft\Bybit\BybitLinear;

class ExchangeSyncOrders extends Command
{
    use Traits\RateLimitsTrait;

    protected $signature = 'antbot:sync-orders';
    protected $description = 'Syncronize postions active orders.';
    protected $debug_flag_counter = 1;

    public function handle()
    {
        // logi('Starting SyncOrders');
        $this->syncOrders();
        // logi('Ending SyncOrders');
        return Command::SUCCESS;
    }

    protected function syncOrders()
    {
        $positions = Position::with('exchange')->get();
        $babyt_rate_limit = 1;
        foreach ($positions as $position) {
            if ($position->exchange->exchange == ExchangesEnum::BYBIT) {
                $this->syncBybitPostionOrders($position);
                if ($babyt_rate_limit % 3 == 0) {
                    sleep(1);
                }
                $babyt_rate_limit++;
            }
        }
    }

    protected function syncBybitPostionOrders(Position $position)
    {
        $host = $position->exchange->is_testnet ? 'https://api-testnet.bybit.com' : 'https://api.bybit.com';
        $bybit = new BybitInverse(
            $position->exchange->api_key,
            $position->exchange->api_secret,
            $host
        );

        $response= $bybit->privates()->getOrder([ // getOrderList
            'symbol' => $position->symbol
        ]);

        if ($response['ret_msg'] == 'OK'){
            $records = collect($response['result']);
            // We clean positions ordes before addding new ones.
            $position->orders()->delete();
            foreach ($records as $record) {
                // if ($this->debug_flag_counter < 10 && $position->symbol == 'FTMUSDT' && $position->side == 'Sell') {
                //     if ($this->debug_flag_counter == 1) {
                //         logi($position);
                //         logi("Position");
                //     }
                //     logi($record);
                //     logi("Record");
                //     $this->debug_flag_counter++;
                // }
                // We only add matching side orders here
                if ($record['position_idx'] == $position->position_idx) {
                    $new_record = Order::create(
                        array_merge($record, ['position_id' => $position->id])
                    );
                }
            }
            $this->checkRateLimits($response['rate_limit_status'], $position->exchange, 'syncOrders');
        } else {
            $this->processApiError($response['ret_msg'], $position->exchange, 'syncOrders');
        }
    }

}
