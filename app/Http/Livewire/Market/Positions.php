<?php

namespace App\Http\Livewire\Market;

use App\Models\Exchange;
use Livewire\Component;
use Lin\Bybit\BybitLinear;

class Positions extends Component
{
    public $title = 'Bybit positions';

    public function render()
    {
        $exchange_data = Exchange::where('name', 'bybit_01')->firstOrFail();
        $bybit = new BybitLinear($exchange_data->api_key, $exchange_data->api_secret);

        return view('livewire.market.positions', compact('bybit'))->layoutData([
            'title' => $this->title,
        ]);
    }
}
