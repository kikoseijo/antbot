<?php

namespace App\Console\Commands;

use App\Enums\ExchangesEnum;
use App\Models\Order;
use App\Models\Position;
use Illuminate\Console\Command;
use Ksoft\Bybit\BybitInverse;
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
        $bybit = new BybitLinear(
            $position->exchange->api_key,
            $position->exchange->api_secret,
            $host
        );
        $query = [ 'symbol' => $position->symbol]; // str_replace('USDT', 'USD', $position->symbol)
        $response= $bybit->privates()->getOrderSearch($query);
        // logi($query);

        if ($response['ret_msg'] == 'OK'){
            $records = collect($response['result']);
            $position->orders()->delete(); // Delete position orders.
            foreach ($records as $record) {
                // We only add matching side orders here
                if ($record['order_status'] == 'New') {
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
