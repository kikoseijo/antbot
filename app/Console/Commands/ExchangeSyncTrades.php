<?php

namespace App\Console\Commands;

use App\Enums\ExchangesEnum;
use App\Models\Symbol;
use App\Models\Trade;
use App\Models\Exchange;
use Illuminate\Console\Command;
use Lin\Bybit\BybitLinear;

class ExchangeSyncTrades extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'antbot:sync-trades';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncronize exchange trade records.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $exchanges = Exchange::with('balances')->where('api_error', 0)->get();
        foreach ($exchanges as $exchange) {
            if ($exchange->exchange == ExchangesEnum::BYBIT) {
                $this->syncBybit($exchange);
            }
        }

        return Command::SUCCESS;
    }

    protected function syncBybit(Exchange $exchange)
    {
        $bybit = new BybitLinear($exchange->api_key, $exchange->api_secret);
        $symbols = Symbol::where('exchange', $exchange->exchange)->get();

        foreach ($symbols as $symbol) {
            if ($exchange->api_error) {
                break;
            }
            $response = $bybit->privates()->getTradeClosedPnlList([
                'symbol' => $symbol->name,
                'limit' => 50,
            ]);
            if ($response['ret_msg'] == 'OK'){
                $current_page = $response['result']['current_page'];
                // logi(\Arr::except($response, ['result.data']));
                $filtered_response = collect($response['result']['data']);
                    // ->filter(function ($value, $key) {
                    //     return $value['data']['size'] > 0;
                    // });
                $this->saveExchangeTrades($exchange, $filtered_response);
            } else {
                // TODO: check more erros with pass or any other.
                if (in_array($response['ret_msg'], ['invalid api_key', 'API key is invalid.'])) {
                    $exchange->api_error = 1;
                    $exchange->save();
                }
                \Log::info("Bybit trade sync {$exchange->name} #{$exchange->id} Error:{$response['ret_msg']}");
            }
            $this->checkRateLimits($response['rate_limit_status'], $exchange);
        }

    }

    protected function saveExchangeTrades(Exchange $exchange, $filtered_response)
    {
        foreach ($filtered_response as $key => $data) {
            Trade::updateOrCreate([
                'position_id' => $data['id'],
                'exchange_id' => $exchange->id,
                'order_id' => $data['order_id']
            ],  \Arr::except($data, ['user_id']));
        }
    }


    protected function checkRateLimits($limit, Exchange $exchange)
    {
        if ($limit < 50){
            \Log::info("Reaching exchange limits {$exchange->name} #{$exchange->id} LIMIT:{$limit}");
            sleep(5);
        }
    }
}
