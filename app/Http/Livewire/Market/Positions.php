<?php

namespace App\Http\Livewire\Market;

use App\Models\Exchange;
use Livewire\Component;
use Ksoft\Bybit\BybitLinear;

class Positions extends Component
{
    public $title = 'Bybit positions';

    public function render()
    {
        $exchange_data = Exchange::where('exchange', 'bybit')->firstOrFail();
        $bybit = new BybitLinear($exchange_data->api_key, $exchange_data->api_secret);
        return view('livewire.market.positions', compact('bybit'))->layoutData([
            'title' => $this->title,
        ]);
    }
}
