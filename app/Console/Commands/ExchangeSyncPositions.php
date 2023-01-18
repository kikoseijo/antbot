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

    // Bitget
    protected function syncBitgetPositions(Exchange $exchange)
    {
        // TODO: nextFlag -> endId
        $client = new BitgetSwap($exchange->api_key, $exchange->api_secret, $exchange->api_frase);
        $response = $client->position()->getAllPosition([
            'productType' => 'umcbl'
        ]);
        if ($response['msg'] == 'success'){
            $records = collect($response['data'])->filter(function ($value, $key) {
                return $value['total'] > 0;
            });
            $this->removeNonExistingPositions($exchange, $records);
            $this->saveBitgetPositions($exchange, $records);
        } else {
            $this->processApiError($response['msg'], $exchange, 'SyncPositions');
        }
    }

    protected function saveBitgetPositions(Exchange $exchange, $records)
    {
        foreach ($records as $data) {
            $size = \Arr::get($data, 'total');
            $side = $data['holdSide'] == 'long' ? 'Buy' : 'Sell';
            $mode = $data['marginMode'] == 'crossed' ? 'BothSide' : 'SingleSide';
            $value = \Arr::get($data, 'marketPrice') * $size;
            Position::updateOrCreate([
                'symbol' => \Arr::get($data, 'symbol'),
                'side' => $side,
                'exchange_id' => $exchange->id
            ],[
                'is_isolated' => $mode == 'fixed',
                'mode' => $mode,
                'size' => \Arr::get($data, 'total'),
                'leverage' => \Arr::get($data, 'leverage'),
                'position_value' => $value, // maybe...
                'entry_price' => \Arr::get($data, 'averageOpenPrice'),
                'position_margin' => \Arr::get($data, 'margin'),
                'realised_pnl' => \Arr::get($data, 'achievedProfits'),
                'unrealised_pnl' => \Arr::get($data, 'unrealizedPL'),
                'liq_price' => \Arr::get($data, 'liquidationPrice'),
                // 'deleverage_indicator' => $data['xxxxxxx'],
                // 'auto_add_margin' => $data['xxxxxxx'],
                // 'position_idx' => $data['xxxxxxx'],
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

    // Bybit
    protected function syncBybitPositions(Exchange $exchange)
    {
        $host = $exchange->is_testnet ? 'https://api-testnet.bybit.com' : 'https://api.bybit.com';
        $client = new BybitLinear($exchange->api_key, $exchange->api_secret, $host);
        $response = $client->privates()->getPositionList();
        if ($response['ret_msg'] == 'OK'){
            $filtered_response = collect($response['result'])->filter(function ($value, $key) {
                return $value['data']['size'] > 0;
            });
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
            Position::updateOrCreate([
                'symbol' => $data['data']['symbol'],
                'side' => $data['data']['side'],
                'exchange_id' => $exchange->id
            ],  \Arr::except($data['data'], ['tp_trigger_by', 'sl_trigger_by', 'user_id']));
        }
    }



}
