<?php

namespace App\Http\Livewire\Market;

use Livewire\Component;
use Ksoft\Bybit\BybitLinear;

class MarketData extends Component
{
    public $title = 'Market Data';

    public function render()
    {
        $bybit = new BybitLinear();

        return view('livewire.market.market-data', compact('bybit'))->layoutData([
            'title' => $this->title,
        ]);
    }
}
