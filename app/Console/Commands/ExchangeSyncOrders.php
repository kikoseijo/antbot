<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Position;
use Illuminate\Console\Command;
use Ksoft\Bybit\BybitLinear;

class ExchangeSyncOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'antbot:sync-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncronize postions active orders.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // logi('Starting SyncOrders');
        $this->syncBybitOrders();
        // logi('Ending SyncOrders');
        return Command::SUCCESS;
    }

    protected function syncBybitOrders()
    {
        $positions = Position::with('exchange')->get();
        $rate_limit = 1;
        foreach ($positions as $position) {
            $host = $position->exchange->is_testnet ? 'https://api-testnet.bybit.com' : 'https://api.bybit.com';
            $bybit = new BybitLinear(
                $position->exchange->api_key,
                $position->exchange->api_secret,
                $host
            );
            $this->syncPositionOrders($bybit, $position);
            if ($rate_limit % 3 == 0) {
                sleep(1);
            }
            $rate_limit++;
        }
    }

    protected function syncPositionOrders(BybitLinear $bybit, Position $position)
    {
        $response= $bybit->privates()->getOrderSearch([
            'symbol' => $position->symbol,
        ]);
        $this->checkRateLimits($response['rate_limit_status'], 'Bybit');
        if ($response['ret_msg'] == 'OK'){
            $records = collect($response['result']);
            $position->orders()->delete(); // We clean old ordes before proceed.
            foreach ($records as $record) {
                $new_record = Order::create(
                    array_merge($record, ['position_id' => $position->id])
                );
            }
        } else {
            logi('Bybit: SyncOrders Error: ' . $response['ret_msg']);
        }
    }

    protected function checkRateLimits($limit, $exchange_name)
    {
        if ($limit < 30 && $limit > 0){
            sleep(3);
            if ($limit < 10){
                \Log::info("Reaching exchange getOrderSearch limits {$exchange_name}LIMIT:{$limit}");
            }
        }
    }
}
