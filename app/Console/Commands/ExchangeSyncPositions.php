<?php

namespace App\Console\Commands;

use App\Enums\ExchangesEnum;
use App\Models\Position;
use App\Models\Exchange;
use Illuminate\Console\Command;
use Ksoft\Bybit\BybitLinear;
use Ksoft\Bitget\BitgetSwap;

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
            } elseif ($exchange->exchange == ExchangesEnum::BITGET) {
                $this->syncBitgetPositions($exchange);
            }
        }
        // logi('Ending SyncPositions');
        return Command::SUCCESS;
    }

    protected function syncBitgetPositions(Exchange $exchange)
    {
        $client = new BitgetSwap($exchange->api_key, $exchange->api_secret, $exchange->api_frase);
        $response = $client->position()->getAllPosition([
            'productType' => 'umcbl'
        ]);
        if ($response['msg'] == 'success'){
            $records = collect($response['data']);
            $this->removeNonExistingPositions($exchange, $records);
            $this->saveBitgetPositions($exchange, $records);
        } else {
            $this->processApiError($response['msg'], $exchange, 'SyncPositions');
        }
    }

    protected function syncBybitPositions(Exchange $exchange)
    {
        $host = $exchange->is_testnet ? 'https://api-testnet.bybit.com' : 'https://api.bybit.com';
        $client = new BybitLinear($exchange->api_key, $exchange->api_secret, $host);
        $response = $client->privates()->getPositionList();
        if ($response['ret_msg'] == 'OK'){
            $filtered_response = collect($response['result'])->filter(function ($value, $key) {
                return $value['data']['size'] > 0;
            });
            // logi(\Arr::get($response, 'result'));
            $this->removeNonExistingPositions($exchange, $filtered_response);
            $this->saveBybitPositions($exchange, $filtered_response);
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

    protected function saveBybitPositions(Exchange $exchange, $records)
    {
        foreach ($records as $key => $data) {
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




    // "openDelegateCount":"0",
    // "available":"0",
    // "locked":"0",
    // "total":"0",
    // "holdMode":"double_hold",
    // "keepMarginRate":"0",
    protected function saveBitgetPositions(Exchange $exchange, $records)
    {
        foreach ($records as $data) {
            $side = $data['holdSide'] == 'long' ? 'Buy' : 'Sell';
            $mode = $data['marginMode'] == 'crossed' ? 'BothSide' : 'SingleSide';
            Position::updateOrCreate([
                'symbol' => $data['symbol'],
                'side' => $side,
                'exchange_id' => $exchange->id
            ],[
                'is_isolated' => $mode == 'fixed',
                'mode' => $mode,
                'leverage' => $data['leverage'],
                'position_value' => $data['marketPrice'], // maybe...
                'entry_price' => $data['averageOpenPrice'],
                'position_margin' => $data['margin'],
                'realised_pnl' => $data['achievedProfits'],
                'unrealised_pnl' => $data['unrealizedPL'],
                // 'deleverage_indicator' => $data['xxxxxxx'],
                // 'auto_add_margin' => $data['xxxxxxx'],
                // 'position_idx' => $data['xxxxxxx'],
                // 'size' => $data['xxxxxxx'],
                // 'liq_price' => $data['xxxxxxx'],
                // 'bust_price' => $data['xxxxxxx'],
                // 'occ_closing_fee' => $data['xxxxxxx'],
                // 'cum_realised_pnl' => $data['xxxxxxx'],
                // 'tp_sl_mode' => $data['xxxxxxx'],
                // 'risk_id' => $data['xxxxxxx'],
                // 'stop_loss' => $data['xxxxxxx'],
                // 'take_profit' => $data['xxxxxxx'],
                // 'trailing_stop' => $data['xxxxxxx'],
            ]);
        }
    }

}
