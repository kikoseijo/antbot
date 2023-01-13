<?php

namespace App\Console\Commands;

use App\Enums\ExchangesEnum;
use App\Models\Position;
use App\Models\Exchange;
use Illuminate\Console\Command;
use Ksoft\Bybit\BybitLinear;

class ExchangeSyncPositions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'antbot:sync-positions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Syncronize exchange open positions.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // logi('Starting SyncPositions');
        $exchanges = Exchange::with('balances')->where('api_error', false)->get();
        foreach ($exchanges as $exchange) {
            if ($exchange->exchange == ExchangesEnum::BYBIT) {
                $this->syncBybit($exchange);
            }
        }

        // logi('Ending SyncPositions');
        return Command::SUCCESS;
    }

    protected function syncBybit(Exchange $exchange)
    {
        $host = $exchange->is_testnet ? 'https://api-testnet.bybit.com' : 'https://api.bybit.com';
        $bybit = new BybitLinear($exchange->api_key, $exchange->api_secret, $host);
        $response = $bybit->privates()->getPositionList();
        if ($response['ret_msg'] == 'OK'){
            $filtered_response = collect($response['result'])->filter(function ($value, $key) {
                return $value['data']['size'] > 0;
            });
            // logi(\Arr::get($response, 'result'));
            $this->removeNonExistingPositions($exchange, $filtered_response);
            $this->saveExchangePositions($exchange, $filtered_response);
        } else {
            // TODO: check more erros with pass or any other.
            if (in_array($response['ret_msg'], ['invalid api_key', 'API key is invalid.'])) {
                $exchange->api_error = 1;
                $exchange->save();
            }
            \Log::info("Bybit SyncPositions {$exchange->name} #{$exchange->id} Error:{$response['ret_msg']}");
        }
        $this->checkRateLimits($response['rate_limit_status'], $exchange);
    }

    protected function removeNonExistingPositions(Exchange $exchange, $filtered_response)
    {
        $symbols = \Arr::pluck($filtered_response, 'data.symbol');
        foreach ($exchange->positions as $exchange_position) {
            if(!in_array($exchange_position->symbol, $symbols)){
                $exchange_position->delete();
            }
        }
    }

    protected function saveExchangePositions(Exchange $exchange, $filtered_response)
    {
        foreach ($filtered_response as $key => $data) {
            Position::updateOrCreate([
                'symbol' => $data['data']['symbol'],
                'side' => $data['data']['side'],
                'exchange_id' => $exchange->id
            ],  \Arr::except($data['data'], ['tp_trigger_by', 'sl_trigger_by']));
        }
    }


    protected function checkRateLimits($limit, Exchange $exchange)
    {
        if ($limit < 30){
            sleep(3);
            if ($limit < 10){
                \Log::info("Reaching SyncPositions exchange limits {$exchange->name} #{$exchange->id} LIMIT:{$limit}");
            }
        }
    }
}
