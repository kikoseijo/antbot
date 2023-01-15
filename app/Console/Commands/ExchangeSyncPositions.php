<?php

namespace App\Console\Commands;

use App\Enums\ExchangesEnum;
use App\Models\Position;
use App\Models\Exchange;
use Illuminate\Console\Command;
use Ksoft\Bybit\BybitLinear;

class ExchangeSyncPositions extends Command
{
    use Traits\RateLimitsTrait;

    protected $signature = 'antbot:sync-positions';
    protected $description = 'Syncronize exchange open positions.';
    protected $debug_flag_counter = 1;

    public function handle()
    {
        // logi('Starting SyncPositions');
        $exchanges = Exchange::with('balances')->where('api_error', false)->get();
        foreach ($exchanges as $exchange) {
            if ($exchange->exchange == ExchangesEnum::BYBIT) {
                $this->syncBybitPositions($exchange);
            }
        }
        // logi('Ending SyncPositions');
        return Command::SUCCESS;
    }

    protected function syncBybitPositions(Exchange $exchange)
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
            $this->checkRateLimits($response['rate_limit_status'], $exchange, 'SyncPositions');
        } else {
            $this->processApiError($response['ret_msg'], $exchange, 'SyncPositions');
        }

    }

    protected function removeNonExistingPositions(Exchange $exchange, $filtered_response)
    {
        // TODO: its deleting one of the sides. must FIX.
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
            // if($this->debug_flag_counter == 1){
            //     logi($data);
            //     logi('Position sync');
            //     $this->debug_flag_counter++;
            // }
            // $data['data']['ref_id'] = $data['data']['user_id'];
            Position::updateOrCreate([
                'symbol' => $data['data']['symbol'],
                'side' => $data['data']['side'],
                'exchange_id' => $exchange->id
            ],  \Arr::except($data['data'], ['tp_trigger_by', 'sl_trigger_by', 'user_id']));
        }
    }

}
