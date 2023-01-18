<?php

namespace App\Console\Commands;

use App\Enums\ExchangesEnum;
use App\Models\Symbol;
use App\Models\Trade;
use App\Models\Exchange;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Ksoft\Bybit\BybitLinear;
use Ksoft\Bitget\BitgetSwap;

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
            } elseif ($exchange->exchange == ExchangesEnum::BITGET) {
                $this->syncBitget($exchange);
            }
        }
        // logi('Ending SyncTrades');
        return Command::SUCCESS;
    }

    // Bitget
    protected function syncBitget(Exchange $exchange)
    {
        $client = new BitgetSwap($exchange->api_key, $exchange->api_secret, $exchange->api_frase);
        $symbols = Symbol::where('exchange', $exchange->exchange)->get();
        $api_limit = 0;
        foreach ($symbols as $symbol) {

            if ($exchange->api_error) {
                break;
            }

            $last_record = Trade::where('symbol', $symbol->name)
                ->where('exchange_id', $exchange->id)
                ->orderBy('created_at', 'desc')
                ->first();

            $start_time = $last_record->created_at->timestamp ?? now()->subMonths(4)->valueOf();

            $more_pages = 1;
            while ($more_pages) {
                $api_limit++;
                $more_pages = $this->syncBitgetTradesForPage($client, $exchange, $symbol->name, $start_time, $more_pages);
                if ($api_limit % 9 == 0) { // Limit rule: 20 times/2s (uid)
                    sleep(1);
                }
            }
        }
    }

    protected function syncBitgetTradesForPage($client, Exchange $exchange, $symbol, $start_time, $next_id = 1)
    {
        $has_more_pages = false;

        $params = [
            'symbol' => $symbol,
            'startTime' => $start_time,
            'endTime' => now()->valueOf(),
            'pageSize' => 50,
        ];

        if ($next_id <> 1) {
            $params = array_merge($params, ['lastEndId' => $next_id]);
        }

        $response = $client->order()->getHistory($params); // getProductHistory

        $res_msg = \Arr::get($response, 'msg');
        if ($res_msg == 'success'){
            $records = collect(\Arr::get($response, 'data.orderList'));
            if ($records->count() > 0) {
                $this->saveBitgetTrades($exchange, $records);
                if (\Arr::get($response, 'data.nextFlag') == true) {
                    return \Arr::get($response, 'data.endId');
                }
            }
        } else {
            $this->processApiError($res_msg, $exchange, 'syncTrades');
        }

        return false;
    }

    protected function saveBitgetTrades(Exchange $exchange, $records)
    {
        foreach ($records as $data) {
            $side = \Arr::get($data, 'side');
            $state = \Arr::get($data, 'state');
            if (( $state == 'filled' || $state == 'partially_filled') &&
                \Str::contains($side, 'close')) {
                    $order_id = \Arr::get($data, 'orderId');
                    $record_side = \Arr::get($data, 'posSide') == 'short' ? 'Sell' : 'Buy';
                    $lot_size = \Arr::get($data, 'size');
                    $create_date = Carbon::createFromTimestamp(\Arr::get($data, 'uTime') / 1000);
                    Trade::updateOrCreate([
                        'exchange_id' => $exchange->id,
                        'order_id' => $order_id,
                    ],[
                        'symbol' => \Arr::get($data, 'symbol'),
                        'side' => $record_side,
                        'position_id' => 12345,
                        'qty' => $lot_size,
                        'fill_count' => \Arr::get($data, 'filledQty'),
                        'leverage' => \Arr::get($data, 'leverage'),
                        'closed_pnl' => \Arr::get($data, 'totalProfits'),
                        'order_price' => \Arr::get($data, 'price'),
                        'order_type' => ucfirst(\Arr::get($data, 'orderType')),
                        'exec_type' => 'Trade',
                        'closed_size' => $lot_size,
                        // 'cum_entry_value' => \Arr::get($data, 'xxxxxxxx'),
                        // 'avg_entry_price' => \Arr::get($data, 'xxxxxx'),
                        // 'cum_exit_value' => \Arr::get($data, 'xxxxxxxx'),
                        'avg_exit_price' => \Arr::get($data, 'priceAvg'),
                        'created_at' => $create_date ,
                        // 'xxxxx' => \Arr::get($data, 'xxxxxxxx'),
                        // 'xxxxx' => \Arr::get($data, 'xxxxxxxx'),
                    ]);
            } else {
                // logi([$state => $side]);
            }
        }
    }

    // Bybit
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
            $records = collect($response['result']['data']);
            $page = $records->count() == 50 ? $page + 1 : 0;
            $this->saveBybitTrades($exchange, $records);
            $this->checkRateLimits($response['rate_limit_status'], $exchange, 'syncTrades');
        } else {
            $page = 0;
            $this->processApiError($response['ret_msg'], $exchange, 'syncTrades');
        }

        return $page;
    }

    protected function saveBybitTrades(Exchange $exchange, $records)
    {
        foreach ($records as $key => $data) {
            Trade::updateOrCreate([
                'position_id' => $data['id'],
                'exchange_id' => $exchange->id,
                'order_id' => $data['order_id']
            ],  \Arr::except($data, ['user_id']));
        }
    }
}
